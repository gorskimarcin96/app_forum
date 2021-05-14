<template>
  <div class="row dark-form-control">
    <div class="my-1 col-6">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text">Order by</span>
        </div>
        <select class="form-control w-auto mr-1" @change="changeGetPage" v-model="postType"
                :disabled="$postPageComponent.$root.$refs.postPage.isSearching">
          <option v-bind:value="type" v-for="type in postTypes">{{ type|capitalize }}</option>
        </select>
      </div>
    </div>

    <div class="my-1 col-6">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text">Search engine</span>
        </div>
        <select class="form-control" @change="changeGetPage" v-model="searchEngineType"
                :disabled="$postPageComponent.$root.$refs.postPage.isSearching">
          <option v-bind:value="type" v-for="type in searchEngineTypes">{{ type|capitalize }}</option>
        </select>
      </div>
    </div>

    <div class="my-1 col-12">
      <div class="input-group input-group-sm">
        <div class="input-group-prepend">
          <span class="input-group-text">Phrase</span>
        </div>
        <input type="text" class="form-control" placeholder="Search forum" v-model="phrase"/>
        <div class="input-group-append">
          <span class="input-group-text">Load time: {{ $postPageComponent.$root.$refs.postPage.fetchPostTime }}ms</span>
          <button class="btn btn-primary btn-sm" type="button" @click="changeGetPage"
                  :disabled="$postPageComponent.$root.$refs.postPage.isSearching">Search
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import {actions, getters} from "../api/apiForum";

export default {
  data() {
    return {
      phrase: '',
      searchEngineType: null,
      searchEngineTypes: ['database', 'elasticsearch'],
      postType: null,
      postTypes: []
    }
  },
  mounted() {
    this.getPostTypes();
  },
  methods: {
    getPostTypes() {
      actions.fetchPostTypes().then(() => {
        this.postTypes = getters.postTypes();
        this.postType = this.postTypes[0];
        this.searchEngineType = this.searchEngineTypes[1];
      });
    },
    changeGetPage: function () {
      this.$postPageComponent.$root.$refs.postPage.getPosts(this.phrase, this.postType, this.searchEngineType);
    }
  }
}
</script>