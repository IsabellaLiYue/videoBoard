<template>
<div id="home" class="main-all">
  <div class="home-head-bottom">
    <div class="col-lg-2 d-inline-block"></div>
    <div class="col-lg-8 col-md-10 col-sm-10 home-head d-inline-block">
      <span text-center d-inline-block home-title>{{video.title}}</span>
      <select class="from-control col-lg-2 col-md-2 col-sm-3 d-inline-block change-option" name="changeOption" @change="select($event)">
        <option value="1">{{user.username}}</option>
        <option value="2">change password</option>
        <option value="3">Switch chinese and English</option>
        <option value="4">check Echart</option>
        <option value="5">logout</option>
        <option value="6">report</option>
        <option value="7">main</option>
      </select>
    </div>
    <div class="col-lg-1 d-inline-block"></div>
  </div>
  <div class="container">
    <div class="row">
      <div class="col-sm"></div>
      <div class="col-lg-7 col-md-9 col-sm-7 home-content-left alert-light">
        <div class="home-content">
        <div id="showVideo">
        <video controls class="image-preview form-control img-fluid" id="videoTime" :src="video.video_path" @timeupdate="updateTime()" @seeked="seekedVideo"></video>
        </div>
          <div class="form-group detail-desc">
            <label class="">description</label>
            <span class="eye">
                <span><icon name="comment" scale="2" class="home-modify"></icon></span>
                <span>{{video.comment_count}}</span>
                <span><icon name="eye" scale="2" class="home-modify"></icon></span>
                <span>{{video.view_count}}</span>
            </span>
            <div class="">{{video.description}}</div>
            <div> --{{videoUser.username}}</div>
          </div>
          <div class="form-group detail-comment">
            <div>Comment</div>
            <textarea class="from-control col-lg-11" rows="3" placeholder="Say something" v-model="comment"></textarea>
            <button type="button" class="btn btn-primary btn-sm d-inline-block" @click="createComment">Comment</button>
          </div>
        </div>
      </div>
      <div class="col-lg-2 col-md-2 col-sm-2" >
        <div class="row home-content" >
          <div class="detail-right-head">Hotest</div>
          <div class="card home-card" v-for="video in videos" :key="video.id">
            <div class="card-body">
              <span class="card-title card-title-me">{{ video.title }}</span>
                <span style="float: right;margin-top: -4px;">
                  <span style="margin-top: 4px;"><icon name="comment" scale="2" class="home-modify"></icon></span>
                  <span>{{video.comment_count}}</span>
                  <span style="margin-top: 4px;"><icon name="eye" scale="2" class="home-modify"></icon></span>
                  <span>{{video.view_count}}</span>
                </span>
                <img :src="video.thumbnail_path" class="img-thumbnail img-responsive d-block home-show-img" @click="getDetail(video.id)">
            </div>
          </div>
        </div>
      </div>
      <div class="col-sm"></div>
    </div>
  </div>
</div>
</template>
<script>
export default {
  data() {
    return {
      video: '',
      videos: '',
      user: '',
      videoUser: '',
      comment: '',
      comments: '',
      currentTime: '',
      commentTime: '',
      videoId: ''
    }
  },
  created: function() {
    this.videoId = this.$route.query.videoId;
    this.$http.get(this.URL.urls.detail, {params : {videoId: this.videoId}})
      .then((response) => {
        if (response.data.user) {
          this.user = response.data.user;
        }
        if (response.data.videoUser) {
          this.videoUser = response.data.videoUser;
        }
        this.video = response.data.video;
        this.videos = response.data.videos;
      }).catch((error) => console.log(error));
    this.init();
  },
  methods:{
    init: function(){
      this.$http.get(this.URL.urls.getComments,
      {params:{ videoId: this.videoId}})
      .then((response) => {
        this.comments = response.data;
       }).catch((error) => console.log(error));
    },
    updateTime: function() {
      this.currentTime = parseInt(document.getElementById("videoTime").currentTime);
      for (var i = 0; i < this.comments.length; i ++) {
        this.commentTime = parseInt(this.comments[i].commented_at);
          if (this.currentTime == this.commentTime) {
            this.showComments(this.comments[i].content);
            this.comments[i]['content'] = "";
          }
        }
    },
    seekedVideo: function() {
      this.comments = [];
      this.init();
    },
    showComments: function(text) {
      var $p = $('<p>' + text + '</p>');
      var x = 0;
      var h = Math.random() * (parseInt($('#showVideo').css('height')) - 24);
      var num = Math.random() * 1.5;
      var r = parseInt(Math.random() * (255 + 1));
      var g = parseInt(Math.random() * (255 + 1));
      var b = parseInt(Math.random() * (255 + 1));
      var timer = setInterval(function(){
      if ($('#showVideo').css("display") !== "none") {
          x += num  > 0.5 ? num : num + 0.5;
      } else {
          x = 0;
      }
      $p.css({
        "white-space" : "nowrap",
        "font-weight" : "bold",
        "font-size" : "24px",
        "position" : "absolute",
        "top" : h,
        "color" : "rgb(" + r + " , " + g + " , " + b + ")",
        "left" : 500 - x
      });
      $('#showVideo').append($p);
      if(parseInt($p.css("left"))<-parseInt($p.width())) {
          $p.remove();
          clearInterval(timer);
          timer = null;
      }
    },1);
  },
    getDetail: function($videoId) {
      this.$http.get(this.URL.urls.detail,
      {params:
        { videoId: $videoId
        }
      })
      .then((response) => {
        if (response.data.user) {
          this.user = response.data.user;
        } else {
          this.user = "";
        }
        if (response.data.videoUser) {
          this.videoUser = response.data.videoUser;
        } else {
          this.videoUser = "";
        }
        this.video = response.data.video;
        this.videos = response.data.videos;
        this.user = this.user;
        this.videoUser = this.videoUser;
        this.videoId = $videoId;
        this.init();
      }).catch((error) => console.log(error));

    },
    createComment: function() {
      this.$http.get(this.URL.urls.isGuest)
      .then((response) => {
        if (response.data.code == "1") {
          var $videoElement = document.getElementById("videoTime");
          var $commentTime = $videoElement.currentTime;
          this.$http.get(this.URL.urls.createComment, {params:{commentTime: $commentTime, comment: this.comment, videoId: this.video.id}})
          .then((response) => {
            this.showComments(this.comment);
            this.comment = "";
          }).catch((error) => console.log(error));
        } else {
          this.$router.push(this.URL.urls.login);
        }
      }).catch((error) => console.log(error));
    },
    select: function(index) {
      var selectIndex = index.target.value;

      if (selectIndex == 5) {
        this.$http.get(this.URL.urls.logout)
        .then((response) => {
          this.$router.push(this.URL.urls.login);
        }).catch((error) => console.log(error));
      }

      if (selectIndex == 2) {
        this.$router.push({name: this.URL.urls.editPassword});
      }
      if (selectIndex == 7) {
        this.$router.push({name: this.URL.urls.main});
      }
      if (selectIndex == 6) {
        this.$http.get(this.URL.urls.isResport)
        .then((response) => {
          if (response.data.code == "0") {
            this.$router.push(this.URL.urls.report);
          }
        }).catch((error) => console.log(error));
      }
    },
  }
}
</script>
<style scoped>
.img-fluid {
    max-width: 100%;
    height: auto;
    max-height: 300px;
}

.home-head {
  border: 1px solid #ccc9c9;
  padding: 1%;
  background-color: #FFFFFF;
}

.left-eye {
  float: right;
}

.home-content {
  background-color: #FFFFFF;
}

.home-content-left {
  margin-right: 2%;
  padding: 2%;
  padding-bottom: 0;
}

.home-show-img {
  width: 287px;
}

.main-all {
  background-color: #d8e1e4;
  padding: 20px;
  border-radius: 5px;
}

.eye {
  width: 18%;
  float: right;
}

.noneError {
  display: none;
}

.change-option {
  font-size: 12px;
  width: 30%;
  float: right;
  margin-top: 1%;
  height: 29px;

}

.home-head-bottom {
  margin-bottom: 3%;
}

.home-head span {
  font-size: 30px;
  margin-left: 38%;
}

.detail-desc {
  text-align: left;
  line-height: 25px;
  margin-left: 2%;
}

.detail-desc label {
  color: #464040;
}

.detail-desc div {
  font-size: 13px;
}

.detail-comment div {
  font-size: 13px;
}

.detail-desc textarea {
  font-size: 13px;
}

.detail-comment {
  margin-left: 2%;
}

.detail-comment label {
  color: #164040;
}

.card-body {
  -ms-flex: 1 1 auto;
  flex: 1 1 auto;
  padding: 4% 2%;
}

.card-body span {
  float: left;
}

.card-title {
  font-size: 12px;
  font-weight: bold;
  max-width: 85px;
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}

.detail-right-head {
  font-size: 16px;
  font-weight: bold;
  margin: 5% 0 5% 0;
}
</style>