<template>
  <div class="container">
    <SearchComponent />
    <table class="table table-bordered mt-3">
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
                <CarTable :cars="clientCars" />
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
  import SearchComponent from './SearchComponent.vue';
  import CarTable from './CarTable.vue';

  export default {
    components: {
      SearchComponent,
      CarTable,
    },
    data() {
      return {
        clients: [],
        currentPage: 1,
        lastPage: 1,
        expandedClient: null,
        clientCars: [],
        loadingCars: false,
      };
    },
    mounted() {
      this.fetchClients(this.currentPage);
    },
    methods: {
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
