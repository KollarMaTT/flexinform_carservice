<template>
    <div>
      <div v-if="loadingServices">Szerviznapló betöltése...</div>
      <div v-else-if="services.length === 0">A kiválasztott autónak nincs szerviznaplója a rendszerben.</div>
      <div v-else>
        <table class="table table-bordered">
          <thead>
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
            <tr v-for="service in services" :key="service.id">
              <td>{{ service.log_number }}</td>
              <td>{{ service.event }}</td>
              <td>{{ service.event_time || car.registered }}</td>
              <td>{{ service.document_id }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </template>
  
  <script>
  import api from '../services/api.js';
  
  export default {
    name: "ServiceTable",
    props: {
      car: {
        type: Object,
        required: true,
      },
    },
    data() {
      return {
        services: [],
        loadingServices: false,
      };
    },
    mounted() {
      this.fetchCarServices();
    },
    methods: {
      fetchCarServices() {
        this.loadingServices = true;
        api.get(`/car/${this.car.id}/services`)
          .then(response => {
            this.services = response.data.services;
            this.loadingServices = false;
          })
          .catch(error => {
            console.error('Hiba a szerviznapló lekérésekor:', error);
            this.loadingServices = false;
          });
      },
    },
  };
  </script>
  