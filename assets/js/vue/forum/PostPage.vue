<template>
  <div>
    <div class="card bg-dark shadow mb-2" v-for="post in posts">
      <div class="card-header">
        <div class="row">
          <div class="col-md-10 col-8 text-justify">
            <a href="#">{{ post.title }}</a>
          </div>
          <div class="col-md-2 col-4 text-right">
            {{ (new Date(post.createdAt)).toLocaleString() }}
          </div>
        </div>
      </div>
      <div class="card-body row">
        <div class="col-md-2 col-4 text-center">
          <a href="#">
            <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
                 class="rounded-circle" width="50" alt="User"/>
            <div>{{ post.user.email }}</div>
          </a>
        </div>
        <div class="col-md-10 col-8 text-justify">
          {{ post.description }}
          <div>
            <img v-bind:src="file.path" v-for="file in post.postFiles" height="200">
          </div>
        </div>
      </div>
      <div class="card-footer">
        <div class="row">
          <div class="col-md-10 col-8">
              <span v-for="(tag, index) in post.tag" class="badge mx-1"
                    v-bind:class="{ 'badge-primary': index%2, 'badge-secondary': !(index%2) }">{{ tag.name }}</span>
          </div>
          <div class="col-md-2 col-4 text-right small">
            <div><i class="fas fa-eye"></i> {{ post.numberEntries }}</div>
            <div><i class="fas fa-comment-alt"></i> {{ 0 }}</div>
          </div>
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
      posts: []
    }
  },
  mounted() {
    this.getPosts();
  },
  methods: {
    getPosts() {
      actions.fetchPosts().then(() => this.posts = getters.posts());
    }
  }
}
</script>