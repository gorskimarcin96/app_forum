import Vue from 'vue';
import axios from 'axios';

const store = Vue.observable({
    posts: [],
});

export const getters = {
    posts: () => store.posts,
    post: () => store.post,
    postComments: () => store.postComments,
    postTypes: () => store.postTypes,
};

export const mutations = {
    setPost: (val) => {
        store.post = val
    },
    setPostComments: (val) => {
        store.postComments = val
    },
    setPosts: (val) => {
        store.posts = val
    },
    setPostTypes: (val) => {
        store.postTypes = val
    },
};

export const actions = {
    fetchPost(id) {
        return axios
            .get(Routing.generate('api_forum_post', {post: id}))
            .then(response => mutations.setPost(response.data));
    },
    fetchPosts(type = 'latest') {
        window.history.pushState(null, null, Routing.generate('forum_index', {type: type}));

        return axios
            .get(Routing.generate('api_forum_post_get', {type: type}))
            .then(response => mutations.setPosts(response.data));
    },
    fetchPostComments(post, page = 1) {
        return axios
            .get(Routing.generate('api_forum_post_comment_get', {post: post, page: page}))
            .then(response => mutations.setPostComments(response.data));
    },
    fetchPostTypes() {
        return axios
            .get(Routing.generate('api_forum_post_get_types'))
            .then(response => mutations.setPostTypes(response.data));
    },
    sendPost(post, tags, files) {
        let formData = new FormData();
        formData.append('data', JSON.stringify({post: post, tags: tags}));
        for (let i = 0; i < files.length; i++) {
            formData.append("files[" + i + "]", files[i]);
        }

        axios.post(Routing.generate('api_forum_post_add'), formData).then();
    },
    sendPostComment(postComment, files) {
        let formData = new FormData();
        formData.append('data', JSON.stringify({postComment: postComment}));
        for (let i = 0; i < files.length; i++) {
            formData.append("files[" + i + "]", files[i]);
        }

        return axios.post(Routing.generate('api_forum_post_comment_add'), formData)
            .then(response => mutations.setPost(response.data));
    }
};
