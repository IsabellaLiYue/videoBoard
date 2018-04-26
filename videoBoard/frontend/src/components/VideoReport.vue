<template>
<div id="home" class="main-all">

  <div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-8 col-md-9 col-sm-7 home-content-left alert-light content-left">
          <select class="from-control col-lg-2 col-md-2 col-sm-3 d-inline-block change-option" name="changeOption" @change="select($event)">
            <option value="1">{{username}}</option>
            <option value="2">change password</option>
            <option value="3">Switch chinese and English</option>
            <option value="4">check Echart</option>
            <option value="5">logout</option>
            <option value="6">main</option>
          </select>
          <div class= "row-title"> Report</div>
          <div class= "row-title1"> Top10(Today)</div>
        <div class="home-content report" id="report">
        </div>
      </div>
      <div class="col-lg-1"></div>
      <div class="col-lg-2"></div>
      <div class="col-lg-8 col-md-9 col-sm-7 home-content-left alert-light content">
        <div class="home-content"></div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Video</th>
                <th scope="col">Title</th>
                <th scope="col">Author</th>
                <th scope="col">Views</th>
                <th scope="col">Comments</th>
                <th scope="col">Created</th>
                <th scope="col">Report</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(video,indexs) in showVideos" :key="video.id">
                <th scope="row">{{indexs + 1}}</th>
                <td style="width: 109px"><img :src="video.thumbnail_path" class="img-thumbnail img-responsive d-block"></td>
                <td>{{video.title}}</td>
                <td>{{video.username}}</td>
                <td>{{video.v_count}}</td>
                <td>{{video.c_count}}</td>
                <td>{{video.created_at}}</td>
                <td class="reportDetail" data-toggle="modal" data-target="#exampleModal" @click="reportDetail(video.id)"><icon name="report" scale="4"></icon></td>
              </tr>
            </tbody>
          </table>
          <div class="more" @click="more">more</div>
      </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content modal_content">
          <div class="modal-header">
            <div class="modal-title title1" id="exampleModalLabel">Report of Video</div>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body report1" id="report1">
           ...
          </div>
        </div>
      </div>
    </div>
  </div>

</div>
</template>
<script>
let echarts = require('echarts/lib/echarts')
require('echarts/lib/chart/bar')
require("echarts/lib/component/polar")
require('echarts/lib/component/tooltip')
require('echarts/lib/component/title')
require('echarts/lib/component/legend')

export default {
  data() {
    return {
      views: [],
      comments:[],
      videos:[],
      showVideos: [],
      sevenView:[],
      sevenComment: [],
      sevenDate: [],
      index: 1
    }
  },
  mounted() {
    this.drawLine();
    this.drawLine1();
  },
  methods: {
    drawLine() {
      let myChart = echarts.init(document.getElementById('report'));
      myChart.setOption({
          tooltip: {},
          angleAxis: {
            type: 'category',
            data: ['video1', 'video2', 'video3', 'video4', 'video5', 'video6', 'video7', 'video8', 'video9', 'video10'],
            z: 10
          },
          radiusAxis: {},
          polar: {},
          series: [{
            type: 'bar',
            data: [],
            coordinateSystem: 'polar',
            name: 'views',
            stack: 'a'
          }, {
            type: 'bar',
            data: [],
            coordinateSystem: 'polar',
            name: 'comments',
            stack: 'a'
          }],
          legend: {
            show: true,
            data: ['views', 'comments']
          }

      });

      this.$http.get(this.URL.urls.getvideos)
        .then((response) => {
            this.videos = response.data;
            this.showVideos = this.videos.filter((item, index, arr) => {
                return index < 3;
            });
            this.comments = this.videos.map((video) => {
                return parseInt(video.c_count);
            });
            this.views = this.videos.map((video) => {
                return parseInt(video.v_count);
            });
            myChart.setOption({
            series: [
            {
              name: 'views',
              data: this.views,
            },
            {
              name: 'comments',
              data: this.comments,
            }
            ]
        });
        }).catch((error) => console.log(error));
    },


    drawLine1() {
      let myChart = echarts.init(document.getElementById('report1'));
      myChart.setOption({
        tooltip : {
          trigger: 'axis',
          axisPointer : {
            type : 'shadow'
          }
        },
        legend: {
          show: true,
          data:['comments','views']
        },
        grid: {
          left: '2%',
          right: '2%',
          bottom: '3%',
          containLabel: true
        },
        xAxis : [{
          type : 'category',
          data : []
        }],
        yAxis : [{
          type : 'value'
        }],
        series : [
        {
          name:'views',
          type:'bar',
          stack: 'a',
          data:[]
        },
        {
          name:'comments',
          type:'bar',
          stack: 'a',
          data:[]
        }
        ]
      });
    },

  more: function() {
    var newArray = this.videos.filter((item, index, arr) => {
        if (this.showVideos.length == 3) {
            return index >= 3 && index < 6;
        } else if (this.showVideos.length == 6) {
            return index >= 6 && index < 9;
        } else if (this.showVideos.length == 9) {
            return index >= 9;
        }
    });

    this.showVideos = [...this.showVideos, ...newArray];
    },
    reportDetail: function(videoId) {
        this.$http.get(this.URL.urls.getDetail,
      {params:
        { videoId: videoId
        }
      })
      .then((response) => {
          this.sevenView = response.data.sevenView;
          this.sevenComment = response.data.sevenComment;
          this.sevenDate = response.data.sevenDate;

          let myChart = echarts.init(document.getElementById('report1'));
          myChart.setOption({
            xAxis : [{
              type : 'category',
              data : this.sevenDate,
            }],
            yAxis : [{
              type : 'value'
            }],
            series : [
            {
              name:'views',
              type:'bar',
              stack: 'a',
              data: this.sevenView
            },
            {
              name:'comments',
              type:'bar',
              stack: 'a',
              data: this.sevenComment
            }]
       });
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
        this.$router.push({name: this.URL.urls.main});
      }
    },
  }
}
</script>
<style scoped>
.change-option {
  left: 82%;
  width: 72px;
  height: 6%;
  font-size: 10px;
}
.row-title {
  text-align: center;
  font-size: 30px;
  font-weight: bold;
  margin-bottom: 2%;
  color: #000000;
}

.content-left {
  height: 600px;
}

.row-title1 {
  margin-left: 17%;
}

.title1 {
  text-align: center;
  margin-left: 38%;
  color: #867d7d;
  font-size: 24px;
  width: 37%;
  font-weight: bold;
}
.home-head {
  border: 1px solid #ccc9c9;
  padding: 1%;
  background-color: #FFFFFF;
}

.report {
    width: 450px;
    height: 450px;
    margin-left: 20%;
}

.modal_content {
  width: 640px;
}

.report1 {
    width: 450px;
    height: 450px;
    margin-left: 20%;
}

.home-content {
  background-color: #FFFFFF;
}

.reportDetail {
  cursor: pointer;
}

.more {
  font-size: 25px;
  text-align: center;
  width: 12%;
  margin-left: 47%;
  cursor: pointer;
}

.home-content-left {
  margin-top: 2%;
  margin-right: 2%;
  padding: 2%;
  padding-bottom: 0;
}

.content {
  margin-top: 5%;
  margin-bottom: 10%;
}
</style>