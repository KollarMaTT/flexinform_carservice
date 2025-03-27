<template>
    <form @submit.prevent="submitSearch">
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
    <div v-if="searchError" class="alert alert-danger mt-2">
        {{ searchError }}
    </div>
    <div v-else-if="searchResult" class="alert alert-success mt-2">
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
  </template>
  
  <script>
  import api from '../services/api.js';

  export default {
    data() {
      return {
        clientNameSearch: '',
        clientCardNumberSearch: '',
        errors: {},
        searchResult: null,
        searchError: null,
      };
    },
    methods: {
      validateForm() {
        this.errors = {};
  
        if (!this.clientNameSearch && !this.clientCardNumberSearch) {
          this.errors.clientNameSearch = 'Egy mező kitöltése kötelező.';
        }
  
        if (this.clientNameSearch && this.clientCardNumberSearch) {
          this.errors.clientNameSearch = 'Maximum egy mező kitöltése engedélyezett.';
        }
  
        if (!this.clientNameSearch && this.clientCardNumberSearch && !/^[a-z0-9]+$/i.test(this.clientCardNumberSearch)) {
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
    },
  };
  </script>
  