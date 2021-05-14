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
      posts: [],
      fetchPostStartTime: 0,
      fetchPostTime: 0,
      isSearching: false,
      intervalSearchPostTime: null
    }
  },
  mounted() {
    this.getPosts();
  },
  created() {
    this.$root.$refs.postPage = this;
  },
  methods: {
    getPosts(phrase = '', type = 'latest', searchEngine = 'elasticsearch') {
      this.isSearching = true;
      this.fetchPostStartTime = Date.now();
      this.setIntervalSearchPostTime();

      actions.fetchPosts(phrase, type, searchEngine).then(() => {
        let posts = getters.posts();
        for (let i = 0; i < posts.length; i++) {
          posts[i].images = [];
          if ('files' in posts[i]) {
            for (const images of posts[i].files) {
              posts[i].images.push(images.path);
            }
          }
        }

        this.posts = posts;
        clearInterval(this.intervalSearchPostTime)
        this.fetchPostTime = Date.now() - this.fetchPostStartTime;
        this.isSearching = false;
      });
    },
    getRoutePost(postId) {
      return Routing.generate('forum_post', {post: postId});
    },
    setIntervalSearchPostTime: function () {
      this.intervalSearchPostTime = setInterval(function () {
        this.fetchPostTime = Date.now() - this.fetchPostStartTime;
      }.bind(this), 25);
    }
  }
}
</script>