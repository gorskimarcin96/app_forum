import '@fortawesome/fontawesome-free/js/fontawesome';
import '@fortawesome/fontawesome-free/js/solid'; // https://fontawesome.com/icons?d=gallery&s=solid&m=free
import '@fortawesome/fontawesome-free/js/regular'; // https://fontawesome.com/icons?d=gallery&s=regular&m=free
import '@fortawesome/fontawesome-free/js/brands'; // https://fontawesome.com/icons?d=gallery&s=brands&m=free
import '@morioh/v-lightbox/dist/lightbox.css';

const $ = require('jquery');
require('bootstrap');
import './../style/main.scss';
import '../bootstrap';

import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import timer from "./vue/Timer";
import Lightbox from '@morioh/v-lightbox'

Vue.use(BootstrapVue);
Vue.use(Lightbox);

new Vue({
    el: "#timer",
    components: {timer}
});

$(document).ready(function () {
    //page scroll
    $("#scroll_up").click(function () {
        $("html, body").animate({scrollTop: 0}, "slow");
        return false;
    });

    //page title
    setInterval(() => setPageTitle(), 2000);

    function setPageTitle() {
        let title = $('title').text();
        if (title.length >= 25) {
            title = title[0] === '_' ? ' ' + title.slice(1) : title;
            title = title.concat(title[0]).slice(1);
            $('title').text(title[0] === ' ' ? '_' + title.slice(1) : title);
        }
    }
});