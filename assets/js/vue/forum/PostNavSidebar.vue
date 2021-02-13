<template>
  <nav class="nav nav-pills flex-column">
    <a v-for="route in routes" class="nav-link active mb-2" v-bind:href="route.route">
      {{ route.name|capitalize }}
    </a>
  </nav>
</template>

<script>
import {actions, getters} from "../api/apiForum";

export default {
  data() {
    return {
      routes: []
    }
  },
  mounted() {
    this.getPostTypes();
  },
  methods: {
    getPostTypes() {
      actions.fetchPostTypes().then(() => {
        for (const postType of getters.postTypes()) {
          this.routes.push({
            route: Routing.generate('forum_index', {type: postType}),
            name: postType
          });
        }
      });
    },
  }
}
</script>