<template>
  <div class="container-xl py-2 overflow-auto h-100" id="post_template">
    <post-component v-bind:post="post" v-if="post!=null"/>

    <div v-for="postComment in postComments">
      <post-comment-component v-bind:postComment="postComment"/>
    </div>

    <PostCommentAdd v-bind:postComment="{post:post.id}" v-if="post!=null"/>
  </div>
</template>

<script>
import {actions, getters} from "../api/apiForum";
import PostComponent from "./PostComponent";
import PostCommentAdd from "./PostCommentAdd";
import PostCommentComponent from "./PostCommentComponent";

export default {
  components: {PostCommentComponent, PostComponent, PostCommentAdd},
  data() {
    return {
      post: null,
      postComments: [],
      page: 1,
      loadComments: false,
      endCommentPages: false
    }
  },
  mounted() {
    this.getPost();

    const listElm = document.querySelector('#post_template');
    listElm.addEventListener('scroll', e => {
      if (listElm.scrollTop + listElm.clientHeight >= listElm.scrollHeight * 0.8 && !this.loadComments && !this.endCommentPages) {
        this.getPostComment(this.page);
      }
    });
    this.getPostComment(this.page);
  },
  methods: {
    getPost() {
      actions.fetchPost(window.location.pathname.split("/")[2]).then(() => {
        let post = getters.post();
        post.images = [];
        for (const images of post.files) {
          post.images.push(images.path);
        }

        this.post = post;
        this.listenNewPostComments();
      });
    },
    getPostComment(page = 1) {
      this.loadComments = true;
      actions.fetchPostComments(window.location.pathname.split("/")[2], page).then(() => {
        let postComments = getters.postComments();
        for (const postComment of postComments) {
          postComment.images = [];
          for (const images of postComment.files) {
            postComment.images.push(images.path);
          }
        }

        this.postComments = [].concat(this.postComments, postComments);
        this.page++;
        this.loadComments = false;
        this.endCommentPages = !(postComments.length > 0);
      });
    },
    getRoutePost(postId) {
      return Routing.generate('forum_post', {post: postId});
    },
    listenNewPostComments() {
      let url = new URL('http://localhost:9090/.well-known/mercure');
      url.searchParams.append('topic', '/post-comment/' + this.post.id);

      const eventSource = new EventSource(url);
      eventSource.onmessage = (event) => {
        this.pushPostComment(JSON.parse(event.data));
      }
    },
    pushPostComment(postComment) {
      postComment.images = [];
      for (const images of postComment.files) {
        postComment.images.push(images.path);
      }
      this.postComments = [].concat(this.postComments, [postComment]);
    }
  }
}
</script>