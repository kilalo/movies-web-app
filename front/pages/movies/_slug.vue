<template>
    <div>
      <NuxtLink to="/movies">
        <b-button variant="dark" class="mb-4">
          Back to the list
        </b-button>
      </NuxtLink>
      <div v-if="load">
          <b-card class="mb-3">
              <b-skeleton width="85%"></b-skeleton>
              <b-skeleton width="55%"></b-skeleton>
              <b-skeleton width="70%"></b-skeleton>
              <b-skeleton width="45%"></b-skeleton>
              <b-skeleton width="85%"></b-skeleton>
              <b-skeleton width="55%"></b-skeleton>
              <b-skeleton width="70%"></b-skeleton>
              <b-skeleton width="45%"></b-skeleton>
          </b-card>
        </div>
        <div v-else>
          <h1 class="pb-5 text-5xl">{{ movie.title }}</h1>
          <div>
            <div
              class="card mb-3">
              <div class="d-flex flex-column flex-lg-row">
                <img class="mx-auto" :src="'https://image.tmdb.org/t/p/w400' + movie.poster_path" />
                <b-card-body class="p-5 card-body d-flex flex-column align-items-around justify-content-around">
                    <div>
                        <div class="mb-3 d-flex flex-wrap justify-content-center">
                          <b-badge class="text-xl mr-2 mb-2" v-for="genre in movie.genres" :key="genre.id">
                            {{ genre.name }}
                          </b-badge>
                        </div>
                    </div>
                    <div>
                      <b-card-sub-title class="text-center text-xl my-2">
                        <b-icon-calendar3></b-icon-calendar3>
                        {{ formateDate(movie.release_date) }}
                      </b-card-sub-title>
                      <vue-circle
                          inner-text
                          class="my-3 d-flex justify-content-center"
                          :progress="movie.vote_average / 10 * 100"
                          :size="150"
                          :reverse="false"
                          line-cap="round"
                          :fill="fill"
                          empty-fill="rgba(0, 0, 0, .1)"
                          insert-mode="append"
                          :thickness="5"
                          :show-percent="false">
                          <span class="text-3xl">{{ movie.vote_average }}</span>
                      </vue-circle>
                    </div>
                    <b-card-text class="text-center text-lg-left text-xl mb-2">{{ movie.overview }}</b-card-text>
                </b-card-body>
              </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
export default {
  data () {
    return {
      load: true,
      movie: {},
      fill: {
        color: '#007bff'
      }
    }
  },
  asyncData ({ params }) {
    const slug = params.slug
    return { slug }
  },
  mounted () {
    this.getMovie()
  },
  methods: {
    async getMovie () {
      await this.$axios.$get('http://127.0.0.1:8000/api/movies/' + this.slug).then((data) => {
        this.movie = data.movie
        this.load = false
      })
    },
    formateDate (d) {
      return new Date(d).toDateString()
    }
  }
}
</script>
