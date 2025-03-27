<template>
  <div class="container">
    <form class="mb-3 mt-3" @submit.prevent="submitSearch">
      <div class="row g-2">
        <div class="col-md-5">
          <label for="clientNameSearch" class="form-label">Ügyfél neve</label>
          <input id="clientNameSearch" type="text" class="form-control" v-model="clientNameSearch">
          <span v-if="errors.clientNameSearch" class="text-danger">{{ errors.clientNameSearch }}</span>
        </div>
        <div class="col-md-5">
          <label for="clientCardNumberSearch" class="form-label">Ügyfél okmányazonosítója</label>
          <input id="clientCardNumberSearch" type="text" class="form-control" v-model="clientCardNumberSearch">
          <span v-if="errors.clientCardNumberSearch" class="text-danger">{{ errors.clientCardNumberSearch }}</span>
        </div>
        <div class="col-md-2 d-flex align-items-end">
          <button type="submit" class="btn btn-primary w-100">Keresés</button>
        </div>
      </div>
    </form>


    <!-- Keresési eredmény -->
    <div v-if="searchError" class="alert alert-danger">
      {{ searchError }}
    </div>
    <div v-else-if="searchResult" class="alert alert-success">
      <table class="table table-bordered ">
        <thead>
          <tr>Keresés eredménye</tr>
          <tr>
            <th>Ügyfél azonosító</th>
            <th>Név</th>
            <th>Okmányazonosító</th>
            <th>Autók száma</th>
            <th>Szerviznapló bejegyzések száma</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>{{ searchResult.id }}</td>
            <td>{{ searchResult.name }}</td>
            <td>{{ searchResult.card_number }}</td>
            <td>{{ searchResult.cars_count }}</td>
            <td>{{ searchResult.services_count }}</td>
          </tr>
        </tbody>
      </table>
    </div>


    <table class="table table-bordered">
      <thead>
        <tr class="table-warning">
          <th colspan="4">Ügyfelek</th>
        </tr>
        <tr>
          <th>Ügyfél azonosító</th>
          <th>Név</th>
          <th>Okmányazonosító</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="client in clients" :key="client.id">
          <tr>
            <td>{{ client.id }}</td>
            <td style="cursor: pointer;" @click="toggleClient(client)">{{ client.name }}</td>
            <td>{{ client.card_number }}</td>
          </tr>
          <tr v-if="expandedClient && expandedClient.id === client.id">
            <td colspan="3">
              <div v-if="loadingCars">Autók betöltése...</div>
              <div v-else-if="clientCars.length === 0">A kiválasztott ügyfélnek nincs autója a rendszerben.</div>
              <div v-else>
                <table class="table table-bordered">
                  <thead >
                    <tr class="table-primary">
                      <th colspan="7">Ügyfél autói</th>
                    </tr>
                    <tr>
                      <th>Autó sorszáma</th>
                      <th>Típus</th>
                      <th>Regisztrálás időpontja</th>
                      <th>Saját márka</th>
                      <th>Balesetek száma</th>
                      <th>Utolsó szerviznapló</th>
                      <th>Utolsó szerviznapló dátuma</th>
                    </tr>
                  </thead>
                  <tbody>
                    <template v-for="car in clientCars" :key="car.id">
                      <tr>
                        <td @click="toggleCar(car)" style="cursor: pointer;">{{ car.car_id }}</td>
                        <td>{{ car.id }}</td>
                        <td>{{ car.registered }}</td>
                        <td>
                          <span class="badge" :class="car.ownbrand === 1 ? 'text-bg-success' : 'text-bg-danger'">
                            {{ car.ownbrand === 1 ? 'Igen' : 'Nem' }}
                          </span>
                        </td>
                        <td>{{ car.accidents }}</td>
                        <td>{{ car.last_service_event || '' }}</td>
                        <td>{{ car.last_service_event_time || ''}}</td>
                      </tr>
                      <tr v-if="expandedCar && expandedCar.id === car.id">
                        <td colspan="7">
                          <div v-if="loadingServices">Szerviznapló betöltése...</div>
                          <div v-else-if="carServices.length === 0">A kiválasztott autónak nincs szerviznaplója a rendszerben.</div>
                          <div v-else>
                            <table class="table table-bordered">
                              <thead >
                                <tr class="table-success">
                                  <th colspan="4">Autó szerviznaplója</th>
                                </tr>
                                <tr>
                                  <th>Alkalom sorszáma</th>
                                  <th>Esemény neve</th>
                                  <th>Esemény időpontja</th>
                                  <th>Munkalap azonosító</th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr v-for="service in carServices" :key="service.id">
                                  <td>{{ service.log_number }}</td>
                                  <td>{{ service.event }}</td>
                                  <td>{{ service.event_time || car.registered}}</td>
                                  <td>{{ service.document_id }}</td>
                                </tr>
                              </tbody>
                            </table>
                          </div>
                        </td>
                      </tr>
                    </template>
                  </tbody>
                </table>
              </div>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <li class="page-item" :class="{ disabled: currentPage === 1 }">
          <a class="page-link" href="#" @click.prevent="prevPage" aria-label="Előző">
            <span aria-hidden="true">&laquo;</span>
          </a>
        </li>
        <li class="page-item" :class="{ disabled: currentPage === lastPage }">
          <a class="page-link" href="#" @click.prevent="nextPage" aria-label="Következő">
            <span aria-hidden="true">&raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </div>

</template>

<script>
  import api from '../services/api.js';

  export default {
    data() {
      return {
        clients: [],
        currentPage: 1,
        lastPage: 1,
        expandedClient: null,
        clientCars: [],
        loadingCars: false,
        expandedCar: null,
        carServices: [],
        loadingServices: false,
        clientNameSearch: '',
        clientCardNumberSearch: '',
        errors: {},
        searchResult: null,
        searchError: null,
      };
    },
    mounted() {
      this.fetchClients(this.currentPage);
    },
    methods: {
      validateForm() {
        this.errors = {};

        if (!this.clientNameSearch && !this.clientCardNumberSearch) {
          this.errors.clientNameSearch = 'Egy mező kitöltése kötelező.';
        }

        if (this.clientNameSearch != '' && this.clientCardNumberSearch != '') {
          this.errors.clientNameSearch = 'Maximum egy mező kitöltése engedélyezett.';
        }

        if(!this.clientNameSearch && this.clientCardNumberSearch != '' && !/^[a-z0-9]+$/i.test(this.clientCardNumberSearch)){
          this.errors.clientCardNumberSearch = 'Csak alfanumerikus karaktereket tartalmazhat.';
        }

        return Object.keys(this.errors).length === 0;
      },
      submitSearch() {
        if (this.validateForm()) {
          api.get(`/search-clients?name=${this.clientNameSearch}&card_number=${this.clientCardNumberSearch}`)
          .then(response => {
            const results = response.data.clients.data;
            console.log(results);
            if (results.length > 1) {
              this.searchError = "Több találat van, kérjük pontosítsa a keresést!";
              this.searchResult = null;
            } else if (results.length === 1) {
              this.searchError = null;
              this.searchResult = results[0];
            } else {
              this.searchError = "Nincs találat.";
              this.searchResult = null;
            }
          })
          .catch(error => {
            if (error.response.status === 422) {
              this.errors = error.response.data.errors;
            }
          });
        }
      },
      fetchClients(page) {
        api.get(`/clients?page=${page}`)
        .then(response => {
          this.clients = response.data.clients.data;
          this.currentPage = response.data.clients.current_page;
          this.lastPage = response.data.clients.last_page;
        })
        .catch(error => {
          console.log('Hiba az ügyfél lekérésekor: ', error);
        });
      },
      toggleClient(client) {
        if (this.expandedClient && this.expandedClient.id === client.id) {
          this.expandedClient = null;
          this.clientCars = [];
        } else {
          this.expandedClient = client;
          this.fetchClientCars(client.id);
        }
      },
      fetchClientCars(clientId) {
        this.loadingCars = true;
        api.get(`/clients/${clientId}/cars`)
        .then(response => {
          this.clientCars = response.data.cars;
          this.loadingCars = false;
        })
        .catch(error => {
          console.error('Hiba az autók lekérésekor:', error);
          this.loadingCars = false;
        });
      },
      toggleCar(car) {
        if (this.expandedCar && this.expandedCar.id === car.id) {
          this.expandedCar = null;
          this.carServices= [];
        } else {
          this.expandedCar = car;
          this.fetchCarServices(car.id);
        }
      },
      fetchCarServices(carId) {
        this.loadingServices = true;
        api.get(`/car/${carId}/services`)
        .then(response => {
          this.carServices = response.data.services;
          this.loadingServices = false;
        })
        .catch(error => {
          console.error('Hiba a  szerviznapló lekérésekor:', error);
          this.loadingServices = false;
        });
      },
      nextPage() {
        if (this.currentPage < this.lastPage) {
          this.fetchClients(this.currentPage + 1);
        }
      },
      prevPage() {
        if (this.currentPage > 1) {
          this.fetchClients(this.currentPage - 1);
        }
      },
    },
  };

</script>

<style scoped>
</style>
