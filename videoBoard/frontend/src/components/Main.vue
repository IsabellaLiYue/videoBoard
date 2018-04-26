<template>
  <div id="home">
    <div class="home-head-buttom">
      <div>{{ message }}</div>
      <div class="col-lg-2 col-md-1 col-sm-1 d-inline-block"></div>
      <div class="panel panel-default col-lg-9 col-md-10 col-sm-10 d-inline-block home-head">
        <span text-center d-inline-block home-title class="home-title">Videos</span>
        <select class="from-control col-lg-2 col-md-2 col-sm-3 d-inline-block change-option" name="changeOption" @change="select($event)">
          <option value="1">{{username}}</option>
          <option value="2">change password</option>
          <option value="3">Switch chinese and English</option>
          <option value="4">Check Echart</option>
          <option value="5">Logout</option>
          <option value="6">Report</option>
        </select>
      </div>
      <div class="col-lg-1 col-md-1 col-sm-1 d-inline-block"></div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-lg-2"></div>
          <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="home-search">
              <div class="col-lg-4 col-md-4 col-sm-4 d-inline-block">
                <div class="input-group input-group-sm mb-3">
                  <input type="text" class="from-control search_text" id="searchText" aria-label="Default" value="" placeholder="Search By name" v-model="searchKey">
                  <div class="input-group-append">
                    <img src="../assets/ICN_Search_15x20.png" class="img-thumbnail" @click="search">
                  </div>
                </div>
              </div>
              <div class="col-lg-7 col-md-6 col-sm-6 col-xs-6 d-inline-block">
                <button type="button" class="btn btn-primary btn-sm d-inline-block home-button" @click="hotest">Hotest</button>
                <button type="button" class="btn btn-primary btn-sm d-inline-block home-button" @click="latest">Latest</button>
                <button type="button" class="btn btn-primary btn-sm d-inline-block home-bsutton" @click="mine">Mine</button>
                <button type="button" class="btn btn-primary btn-sm d-inline-block home-create" @click="create">Create</button>
              </div>
            </div>
            <div class="row home-content" >
              <div class="col-lg-3 col-md-6 col-sm-12 home-content-bottom" v-for="video in videos" :key="video.id">
                <div class="card card-small">
                  <div class="card-body">
                    <h5 class="card-title">{{ video.title }}
                      <icon name="eye" scale="2"></icon>
                      <span>{{video.view_count}}</span>
                      <icon name="comment" scale="2"></icon>
                      <span>{{video.comment_count}}</span>
                    </h5>
                    <img :src="video.thumbnail_path" class="img-thumbnail img-responsive d-block imaged" @click="detail(video.id)">
                    <div class="text-info">
                      <div class="text-info d-inline home-size">{{video.user.username}}</div>
                        <div class="d-lg-table delete-edit">
                          <span v-if="video.user.id == userId" class="rubbish-bin" data-toggle="modal" data-target="#deleModel" @click="deleteIcon(video.id)">
                            <icon name="delete" scale="2" ></icon>
                          </span>
                          <span v-if="video.user.id == userId" class="rubbish-bin" @click="editIcon(video.id)">
                            <icon name="edit" scale="2" ></icon>
                          </span>
                        </div>
                      <div class="text-muted d-block home-size">{{video.created_at}}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal fade" id="deleModel" tadindex="-1" role="dialog" aria-labelledby="exampleModelLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModelLabel">Video Board</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>
                    <div class="modal-body">Are you sure delete this video?</div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal" @click="No">cancel</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal" @click="Yes()">submit</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="more" @click="more">more</div>
          </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name:'ImageUploader',
  data() {
    return {
    title: '',
    description: '',
    image: '',
    video: '',
    videos: '',
    formData: new FormData(),
    userId: '',
    searchKey: '',
    currentPage: '1',
    searchType: 'latest',
    searchMine: 'no',
    username:'',
    message: '',
    videoId: '',
    user: '',
    videoUser:'',
    currentDate: ''
    }
  },
  created: function() {
    if (this.$route.query.user != null) {
      $user = this.$route.query.user;
      this.username = $user.username;
      this.userId = $user.id;
    }
    this.currentDate = new Date();
    this.show();
  },
  methods:{
    create() {
      this.$http.get(this.URL.urls.isGuest)
      .then((response) => {
        if (response.data.code == "1") {
          this.$router.push(this.URL.urls.createVideo);
        } else {
          this.$router.push(this.URL.urls.login);
        }
      }).catch((error) => console.log(error));
    },
    detail: function($videoId) {
      this.$router.push({ name: this.URL.urls.details, query:{videoId: $videoId}});
    },
    No: function() {
      this.show();
    },
    deleteIcon($videoId) {
      this.videoId = $videoId;
    },
    editIcon($videoId) {
      this.$http.get(this.URL.urls.getVideo,
      {params:
        { videoId: $videoId
        }
      })
      .then((response) => {
        this.$router.push(
          { name: this.URL.urls.editVideo,
            query:
              {video: response.data
              }
          });
      }).catch((error) => console.log(error));

    },
    Yes: function() {
      this.$http.get(this.URL.urls.deletes,
      {params:
        { videoId: this.videoId
        }
      })
      .then((response) => {
        this.show();
        if (response.data.code == '1') {
          this.message = "Delete successfully!";

        } else {
          this.message = "Fail to delete";
        }
      }).catch((error) => console.log(error));
    },
    more: function() {
      this.currentPage = parseInt(this.currentPage) + 1;
      this.$http.get(this.URL.urls.show,
      {params:
        { searchKey: this.searchKey,
          searchType: this.searchType,
          currentPage: this.currentPage,
          searchMine: this.searchMine
        }})
      .then((response) => {
        this.videos = [...this.videos, ...response.data.videos];
        this.username = response.data.username;
        this.userId = response.data.id;
      }).catch((error) => console.log(error));
    },
    search:function() {
      this.show();
    },
    hotest: function() {
      this.searchType = 'hotest';
      this.show();

    },
    latest: function() {
      this.searchType = 'latest';
      this.show();

    },
    mine: function() {
      this.$http.get(this.URL.urls.isGuest)
      .then((response) => {
        if (response.data.code == "1") {
          this.searchMine = 'yes';
          this.show();
        } else {
          this.$router.push(this.URL.urls.login);
        }
      }).catch((error) => console.log(error));


    },
    show: function() {
      this.currentPage = "1";
      this.$http.get(this.URL.urls.show,
      {params:
        { searchKey: this.searchKey,
          searchType: this.searchType,
          currentPage: this.currentPage,
          searchMine: this.searchMine
        }
      })
      .then((response) => {
        this.videos = response.data.videos;
        this.userId = null;
        if (response.data.username) {
          this.username = response.data.username;
        }
        if (response.data.id) {
          this.userId = response.data.id;
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
.more {
  font-size: 25px;
  text-align: center;
  width: 12%;
  margin-left: 47%;
  cursor: pointer;
}

.delete-edit {
  float: right;

}

.card-small {
  height: 238px;
}

.home-content-bottom {
  margin-bottom: 24px;
}

#home {
  border:1px solid #A0A0A0;
  background-color: #A0A0A0;
  padding: 20px;
  border-radius: 5px;
  /* margin-top: 4%; */
  background-color: #d2d6de;
}

.home-head-buttom {
  margin-bottom: 3%;
}

.home-head {
  border: 1px solid #A0A0A0;
  padding: 1%;
  background-color: #FFFFFF;
}

.home-search {
  border: 1px solid #A0A0A0;
  padding-top: 15px;
  margin-bottom: 5%;
  background-color: #FFFFFF;
}

.home-head span {
  font-size: 22px;
}

.imaged {
  max-height: 135px;
}

.home-bsutton {
  margin-left: 3%;
}

.change-option {
  height: 33px;
  font-size: 12px;
  float: right;
  width: 30%;
}

.home-button {
  margin-left: 3%;
}

.home-create {
  float: right;
}

.home-size {
  font-size: 12px;
}

.home-title {
  margin-left: 44%;
}
</style>