import Vue from 'vue';
import post_page from "./vue/forum/PostPage";
import post_nav_sidebar from "./vue/forum/PostNavSidebar";
import post_search from "./vue/forum/PostSearch";

//vue filters
Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
});

//vue components
const post_page_component = new Vue({
    el: "#post_page",
    components: {post_page}
});
Vue.prototype.$postPageComponent = post_page_component;
new Vue({
    el: "#post_nav_sidebar",
    components: {post_nav_sidebar}
});
new Vue({
    el: "#post_search",
    components: {post_search}
});
