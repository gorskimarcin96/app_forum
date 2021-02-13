<template>
  <div>
    <div v-for="post in posts">
      <post-component v-bind:post="post"/>
    </div>
  </div>
</template>

<script>
import {actions, getters} from "../api/apiForum";
import PostComponent from "./PostComponent";

export default {
  components: {PostComponent},
  data() {
    return {
      posts: []
    }
  },
  mounted() {
    this.getPosts();
  },
  created() {
    this.$root.$refs.postPage = this;
  },
  methods: {
    getPosts(type) {
      actions.fetchPosts(type).then(() => {
        let posts = getters.posts();
        for (let i = 0; i < posts.length; i++) {
          posts[i].images = [];
          for (const images of posts[i].postFiles) {
            posts[i].images.push(images.path);
          }
        }

        this.posts = posts;
      });
    },
    getRoutePost(postId) {
      return Routing.generate('forum_post', {post: postId});
    }
  }
}
</script>