<template>
    <div>
        <h1 class="pb-4 text-5xl">Movies list</h1>
        <div v-if="load">
            <b-row>
                <b-col
                    v-for="index in 10"
                    :key="index"
                    cols="12"
                    lg="6">
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
                </b-col>
            </b-row>
        </div>
        <div v-else>
            <div class="d-md-flex">
              <div class="text-2xl mr-2 mb-2">
                <b-icon-sort-down></b-icon-sort-down>
                <span>Sort :</span>
              </div>
              <b-button
                class="sort-button mr-1 mb-2"
                :class="currentSortBy === 'title' ? 'bg-primary' : ''"
                value="title"
                @click="changeSort"
                pill>
                <template v-if="currentSortBy === 'title'">
                  <template v-if="currentSortType == 'ASC'">
                    <b-icon-sort-alpha-down></b-icon-sort-alpha-down>
                  </template>
                  <template v-else>
                    <b-icon-sort-alpha-up></b-icon-sort-alpha-up>
                  </template>
                </template>
                Title
              </b-button>
              <b-button
                class="sort-button mr-1 mb-2"
                :class="currentSortBy === 'release_date' ? 'bg-primary' : ''"
                value="release_date"
                @click="changeSort"
                pill>
                <template v-if="currentSortBy === 'release_date'">
                  <template v-if="currentSortType == 'ASC'">
                    <b-icon-sort-alpha-down></b-icon-sort-alpha-down>
                  </template>
                  <template v-else>
                    <b-icon-sort-alpha-up></b-icon-sort-alpha-up>
                  </template>
                </template>
                Release date
              </b-button>
              <b-button
                class="sort-button mr-1 mb-2"
                :class="currentSortBy === 'vote_average' ? 'bg-primary' : ''"
                value="vote_average"
                @click="changeSort"
                pill>
                <template v-if="currentSortBy === 'vote_average'">
                  <template v-if="currentSortType == 'ASC'">
                    <b-icon-sort-alpha-down></b-icon-sort-alpha-down>
                  </template>
                  <template v-else>
                    <b-icon-sort-alpha-up></b-icon-sort-alpha-up>
                  </template>
                </template>
                Note
              </b-button>
            </div>
            <div>
              <div class="text-2xl mb-2">
                <b-icon-filter></b-icon-filter>
                <span>Filters :</span>
              </div>
              <div class="row mx-0">
                <b-form-select
                  text-field="name"
                  value-field="id"
                  v-model="selectedGenre"
                  @change="getMovies"
                  :options="genres"
                  class="col-12 col-sm-6 mb-3">
                  <template #first>
                    <b-form-select-option :value="null">-- Select a genre --</b-form-select-option>
                  </template>
                </b-form-select>
                <b-form-select
                  v-model="selectedVoteAverage"
                  @change="getMovies"
                  :options="voteAverageRange"
                  class="col-12 col-sm-6 mb-3">
                    <template #first>
                      <b-form-select-option :value="null">-- Select a vote average --</b-form-select-option>
                    </template>
                </b-form-select>
              </div>
              <div>
                <b-input-group size="sm" class="mb-2">
                  <b-input-group-prepend is-text>
                    <b-icon icon="search"></b-icon>
                  </b-input-group-prepend>
                  <b-form-input
                    v-model="q"
                    placeholder="Search by title..."
                    @input="updateText">
                  </b-form-input>
                </b-input-group>
              </div>
            </div>
            <pagination
              class="my-3"
              :limit="5"
              align="center"
              :data="datas"
              @pagination-change-page="changePage">
            </pagination>
            <b-row v-if="datas.data.length">
                <b-col
                    v-for="el in datas.data"
                    :key="el.id"
                    cols="12"
                    lg="6"
                    class="mb-3">
                    <b-card
                        :img-src="'https://image.tmdb.org/t/p/w200' + el.poster_path"
                        img-alt="Card image"
                        img-left
                        class="h-100">
                        <b-card-body class="p-0 h-100 card-body d-flex flex-column align-items-around justify-content-between">
                            <div>
                                <b-card-title class="text-xl font-weight-bold">{{ el.title }}</b-card-title>
                                <b-card-sub-title class="mb-2">
                                   <b-icon-calendar3></b-icon-calendar3>
                                   {{ formateDate(el.release_date) }}
                                  </b-card-sub-title>
                            </div>
                            <vue-circle
                                inner-text
                                class="d-flex justify-content-center"
                                :progress="el.vote_average / 10 * 100"
                                :size="100"
                                :reverse="false"
                                line-cap="round"
                                :fill="fill"
                                empty-fill="rgba(0, 0, 0, .1)"
                                insert-mode="append"
                                :thickness="5"
                                :show-percent="false">
                                <span class="text-3xl">{{ el.vote_average }}</span>
                            </vue-circle>
                            <NuxtLink class="mx-auto" :to="'/movies/' + el.slug">
                              <b-button variant="dark">
                                Show more
                              </b-button>
                            </NuxtLink>
                        </b-card-body>
                    </b-card>
                </b-col>
            </b-row>
            <div class="mt-3 text-2xl text-center" v-else>
              No movies with your request !
            </div>
            <pagination
              class="my-3"
              :limit="5"
              align="center"
              :data="datas"
              @pagination-change-page="changePage">
            </pagination>
        </div>
    </div>
</template>

<script>
import { debounce } from 'lodash'
export default {
  data () {
    return {
      currentPage: 1,
      load: true,
      datas: {},
      genres: {},
      fill: {
        color: '#007bff'
      },
      q: '',
      voteValue: null,
      selectedGenre: null,
      selectedVoteAverage: null,
      voteAverageRange: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
      currentSortType: 'ASC',
      currentSortBy: 'title'
    }
  },
  mounted () {
    this.getGenres()
    this.getMovies()
  },
  methods: {
    async getMovies () {
      this.load = true
      await this.$axios.$get(
        'http://127.0.0.1:8000/api/movies?page=' +
        this.currentPage + '&sort_by=' +
        this.currentSortBy + '&sort_type=' +
        this.currentSortType + (this.q ? '&q=' + this.q : '') +
        (this.selectedGenre ? '&genre_id=' + this.selectedGenre : '') +
        (this.selectedVoteAverage ? '&vote_average=' + this.selectedVoteAverage : '')
      ).then((data) => {
        this.datas = data.datas
        this.load = false
      }).catch((response) => {
        console.error(response)
      })
    },
    async getGenres () {
      await this.$axios.$get('http://127.0.0.1:8000/api/genres').then((data) => {
        this.genres = data.datas
      }).catch((response) => {
        console.error(response)
      })
    },
    changePage (pageNumber) {
      this.currentPage = pageNumber
      this.getMovies()
    },
    formateDate (d) {
      return new Date(d).toDateString()
    },
    changeSort (event) {
      if (event.target.localName === 'svg') {
        event.target.parentElement.click()
      } else {
        if (this.currentSortType === 'ASC') {
          this.currentSortType = 'DESC'
        } else {
          this.currentSortType = 'ASC'
        }
        this.currentSortBy = event.target.value
        this.getMovies()
      }
    },
    updateText: debounce(function (e) {
      this.q = e
      this.getMovies()
    }, 500)
  }
}
</script>
