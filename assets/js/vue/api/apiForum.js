import Vue from 'vue';
import axios from 'axios';

const store = Vue.observable({
    posts: [],
});

export const getters = {
    posts: () => store.posts,
};

export const mutations = {
    setPosts: (val) => {
        store.posts = val
    },
};

export const actions = {
    fetchPosts() {
        return axios
            .get(Routing.generate('api_forum_post_get'))
            .then(response => mutations.setPosts(response.data));
    },
    sendPost(post, tags, files) {
        let formData = new FormData();
        formData.append('post', JSON.stringify(post));
        formData.append('tags', tags);
        for (let i = 0; i < files.length; i++) {
            formData.append("files[" + i + "]", files[i]);
        }

        axios.post(Routing.generate('api_forum_post_add'), formData).then();
    }
};