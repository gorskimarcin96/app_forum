<template>
  <div class="card bg-dark shadow mb-2">
    <div class="card-body row">
      <div class="col-md-2 col-4 text-center">
        <a href="#">
          <img src="https://bootdey.com/img/Content/avatar/avatar1.png"
               class="rounded-circle" width="50" alt="User"/>
          <div>{{ postComment.user.email }}</div>
        </a>
      </div>
      <div class="col-md-10 col-8 text-justify">
        <div class="text-right">{{ postComment.formatCreatedAt }}</div>
        {{ postComment.description }}
        <lightbox :items="postComment.images" v-if="postComment.images && postComment.images.length"
                  v-bind:cells="postComment.images.length>=4?4:postComment.images.length"></lightbox>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  props: {
    postComment: {
      id: null,
      title: null,
      description: null,
      user: null,
      formatCreatedAt: null,
      images: [],
    }
  }
}
</script>