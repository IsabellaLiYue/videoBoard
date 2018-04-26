import Vue from 'vue'
import App from './App'
import router from './router'
import axios from 'axios'
import $ from 'jquery'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.min'
import Icon from 'vue-svg-icon/Icon.vue'

axios.defaults.withCredentials = true;

const URL = (function(){
  const frontend_prefix = "http://192.168.10.10:8000/?#";
  // const frontend_prefix = "http:www.isabellali.video-board.com/?#";
  const urls = {
    login: "/login",
    register: "/register",
    main: "Main",
    mainPath: "/main",
    createVideo: "/createEditVideo",
    details: "Detail",
    editVideo: "EditVideo",
    editPassword: "EditPassword",
    reportDetail: "/report-detail",
    report: "/video-report",

    // userLogin: "http://video-board.test/user/login",
    // isLogin: "http://video-board.test/user/is-login",
    // upload: "http://video-board.test/video/upload",
    // detail: "http://video-board.test/video/detail",
    // getComments: "http://video-board.test/video/get-comments",
    // createComment: "http://video-board.test/video/create-comment",
    // logout: "http://video-board.test/user/logout",
    // changePassword: "http://video-board.test/user/changepassword",
    // updateVideo: "http://video-board.test/video/update-video",
    // isGuest: "http://video-board.test/video/is-guest",
    // getVideo: "http://video-board.test/video/get-video",
    // deletes: "http://video-board.test/video/deletes",
    // show: "http://video-board.test/video/show",
    // registerUrl: "http://video-board.test/user/register",
    // getvideos: "http://video-board.test/video/get-videos",
    // getReportVideos: "http://video-board.test/video/get-report",
    // getDetail: "http://video-board.test/video/get-detail",

    userLogin: "http://www.api.isabellali.videoboard.com/user/login",
    isLogin: "http://www.api.isabellali.videoboard.com/user/is-login",
    upload: "http://www.api.isabellali.videoboard.com/video/upload",
    detail: "http://www.api.isabellali.videoboard.com/video/detail",
    getComments: "http://www.api.isabellali.videoboard.com/video/get-comments",
    createComment: "http://www.api.isabellali.videoboard.com/video/create-comment",
    logout: "http://www.api.isabellali.videoboard.com/user/logout",
    changePassword: "http://www.api.isabellali.videoboard.com/user/changepassword",
    updateVideo: "http://www.api.isabellali.videoboard.com/video/update-video",
    isGuest: "http://www.api.isabellali.videoboard.com/video/is-guest",
    getVideo: "http://www.api.isabellali.videoboard.com/video/get-video",
    deletes: "http://www.api.isabellali.videoboard.com/video/deletes",
    show: "http://www.api.isabellali.videoboard.com/video/show",
    registerUrl: "http://www.api.isabellali.videoboard.com/user/register",
    getvideos: "http://www.api.isabellali.videoboard.com/video/get-videos",
    getReportVideos: "http://www.api.isabellali.videoboard.com/video/get-report",
    getDetail: "http://www.api.isabellali.videoboard.com/video/get-detail",
    isResport: "http://www.api.isabellali.videoboard.com/user/is-resport",


  }

  return {
    frontend_prefix: frontend_prefix,
    urls: urls
  }

})()

Vue.config.productionTip = false;
Vue.prototype.$http = axios;
Vue.prototype.URL = URL;
Vue.component('icon', Icon);

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})


