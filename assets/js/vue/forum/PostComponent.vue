<template>
  <div class="card bg-dark shadow mb-2" v-if="post.id !== null">
    <div class="card-header">
      <div class="row">
        <div class="col-md-10 col-8 text-justify">
          <a v-bind:href="getRoutePost(post.id)">{{ post.title }}</a>
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
        <lightbox :items="post.images"
                  v-bind:cells="post.images.length>=4?4:post.images.length"></lightbox>
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
          <div><i class="fas fa-comment-alt"></i> {{ post.commentNumber }}</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    post: {
      id: null,
      title: null,
      description: null,
      user: null,
      createdAt: null,
      images: [],
      tag: [],
      numberEntries: 0,
      commentNumber: 0
    }
  },
  methods: {
    getRoutePost(postId) {
      return Routing.generate('forum_post', {post: postId});
    }
  }
}
</script>