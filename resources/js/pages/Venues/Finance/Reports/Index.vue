<template>
  <div>
    <div class="mb-12 flex justify-between">
      <div>
        <div class="font-semibold text-lg">Rapportage</div>
        <div class="text-sm">Krijg hier inzicht in de financien van je onderneming</div>
      </div>
      <div>

      </div>
    </div>

    <div class="grid grid-cols-3 gap-3 mb-12">
      <div>
        <label for="flatpickr-from" class="font-semibold">Van</label>
        <flat-pickr
            id="flatpickr-from"
            v-model="from"
            :config="config"
            class="input cursor-pointer"
            :placeholder="from ?? 'Selecteer een datum'"
        />
      </div>
      <div>
        <label for="flatpickr-to" class="font-semibold">Tot</label>
        <flat-pickr
            id="flatpickr-to"
            v-model="to"
            :config="config"
            class="input cursor-pointer"
            :placeholder="to ?? 'Selecteer een datum'"
        />
      </div>
      <div class="items-end justify-end flex">
        <button class="btn btn-primary" @click="fetchReports">Ophalen</button>
      </div>
    </div>

    <div class="mb-8 grid grid-cols-1 lg:grid-cols-4 gap-8">
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Omzet (incl. btw)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency(statistics.revenue_incl) }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Omzet (excl. btw)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency(statistics.revenue_excl) }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">BTW laag (9%)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency(statistics.tax_low) }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">BTW hoog (21%)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency(statistics.tax_high) }}</p>
      </div>
    </div>

    <div class="mb-12 grid grid-cols-1 lg:grid-cols-4 gap-8">
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Klantenaantal</h2>
        <p class="font-semibold text-3xl">{{ statistics.customer_count }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Totaal reserveringen</h2>
        <p class="font-semibold text-3xl">{{ statistics.reservations_count }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Gemiddelde klantbesteding (incl. btw)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency(statistics.revenue_incl / statistics.customer_count || 0) }}</p>
      </div>
      <div class="shadow rounded-lg overflow-x-auto bg-gray-50 p-4">
        <h2 class="text-sm text-gray-600">Gemiddelde besteding (incl. btw)</h2>
        <p class="font-semibold text-3xl">{{ $filters.currency((statistics.revenue_incl / statistics.reservations_count || 0)) }}</p>
      </div>
    </div>

<!--    <div v-if="charts" class="grid grid-cols-4 gap-4 font-semibold">-->
<!--      <div>-->
<!--        <p>Reserveringen per dag</p>-->
<!--        <apexchart width="375" :series="charts.reservations.series" :options="charts.reservations.options"></apexchart>-->
<!--      </div>-->
<!--      <div>-->
<!--        <p>Reserveringen per dag</p>-->
<!--        <apexchart width="375" :series="series" :options="options"></apexchart>-->
<!--      </div>-->
<!--      <div>-->
<!--        <p>Reserveringen per dag</p>-->
<!--        <apexchart width="375" :series="series" :options="options"></apexchart>-->
<!--      </div>-->
<!--      <div>-->
<!--        <p>Reserveringen per dag</p>-->
<!--        <apexchart width="375" :series="series" :options="options"></apexchart>-->
<!--      </div>-->
<!--    </div>-->
  </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import 'flatpickr/dist/flatpickr.css';
import {DateTime} from "luxon";

export default {
  name: "Index",
  components: {flatPickr},
  data(unit) {
    return {
      test: 0,
      config: {
        altFormat: 'M j, Y',
        altInput: true,
        dateFormat: 'd-m-Y',
        inline: false,
        showMonths: 1,
        monthSelectorType: 'static',
        locale: {
          firstDayOfWeek: 1
        },
      },

      statistics: {
        reservations_count: 0,
        revenue: 0,
        revenue_incl: 0,
        revenue_excl: 0,
        tax_low: 0,
        tax_high: 0,
        customer_count: 0,
      },

      charts: null,

      options: {
        chart: {
          id: 'vuechart-example'
        },
        xaxis: {
          categories: [1991, 1992, 1993, 1994, 1995, 1996, 1997, 1998]
        }
      },
      series: [{
        name: 'series-1',
        data: [30, 40, 45, 50, 49, 60, 70, 91]
      }],

      from: DateTime.now().startOf('week', {useLocaleWeeks: true}).toFormat('dd-MM-yyyy'),
      // from: DateTime.local().setLocale('nl-NL').startOf('week').toISO(),
      to: DateTime.local().setLocale('nl-NL').endOf('week', {useLocaleWeeks: true}).toFormat('dd-MM-yyyy'),
    }
  },

  methods: {
    fetchReports() {
      axios.get('/venues/' + this.$route.params.venue + '/reports', {
        params: {
          from: this.from,
          to: this.to,
        }
      })
          .then(response => {
            this.statistics.reservations_count = response.data.data.reservations_count
            this.statistics.revenue_incl = response.data.data.revenue_incl
            this.statistics.revenue_excl = response.data.data.revenue_excl
            this.statistics.tax_low = response.data.data.tax_low
            this.statistics.tax_high = response.data.data.tax_high
            this.statistics.customer_count = response.data.data.customer_count
            this.charts = response.data.data.charts;
            console.log(response.data.data.reservations_count)
          })
    }
  },

  mounted() {
    this.fetchReports();
  },

  watch: {
    from: function() {
      console.log(this.from)
    //   this.from = DateTime.fromFormat(this.from, 'dd-MM-yyyy');
    //   this.fetchReports();
    },
    //
    to: function() {
      console.log(this.to)
    //   this.to = DateTime.fromFormat(this.to, 'dd-MM-yyyy');
    //   this.fetchReports();
    },
  }
}
</script>
