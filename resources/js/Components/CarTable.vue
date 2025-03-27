<template>
    <div>
      <table class="table table-bordered">
        <thead>
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
          <template v-for="car in cars" :key="car.id">
            <tr>
              <td @click="toggleCar(car)" style="cursor: pointer;">{{ car.car_id }}</td>
              <td>{{ car.type }}</td>
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
                <ServiceTable :car="car" />
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import ServiceTable from './ServiceTable.vue';
  
  export default {
    name: "CarTable",
    components: {
      ServiceTable,
    },
    props: {
      cars: {
        type: Array,
        required: true,
      },
    },
    data() {
      return {
        expandedCar: null,
      };
    },
    methods: {
      toggleCar(car) {
        if (this.expandedCar && this.expandedCar.id === car.id) {
          this.expandedCar = null;
        } else {
          this.expandedCar = car;
        }
      },
    },
  };
  </script>
  