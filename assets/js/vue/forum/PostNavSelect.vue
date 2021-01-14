<template>
  <select class="form-control w-auto mr-1" @change="changeGetPage" v-model="select">
    <option v-bind:value="postType" v-for="postType in postTypes">{{ postType|capitalize }}</option>
  </select>
</template>

<script>
import {actions, getters} from "../api/apiForum";

export default {
  data() {
    return {
      select: null,
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
        this.select = this.postTypes[0];
      });
    },
    changeGetPage: function () {
      this.$postPageComponent.$root.$refs.postPage.getPosts(this.select);
    }
  }
}
</script>