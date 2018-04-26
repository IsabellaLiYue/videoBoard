<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-6 login">
        <div class="content">
          <div class="header clearfix">
            <h5>Create Video</h5>
            <div class="float-right"><router-link to="/main"><img src="../assets/BTN_Close_16x16.png" /></router-link></div>
          </div>

          <form>
            <div class="form-group">
              <label for="formGroupExampleInput" class="title">Title</label>
              <input type="text" class="form-control input_title" id="inlineFormInputGroupUsername" placeholder="Please enter the title" @blur="validationTitle" v-model="title" >
              <span class="text-danger input-text" :class="{noneError:validateTitle}">Video title cannot be blank</span>
            </div>

            <div class="form-group">
              <label for="exampleFormControlFile1" class="video">Video</label>
              <div>
                <button type="button" class="btn video-button" @click="chooseVideo">{{ videoName }}</button>
                <input style="display: none" ref="fileVideoInput" type="file" accept="video/*" @change="onVideoChange">
              </div>
              <div>
                <video class="video_image" :src="video" controls></video>
              </div>
            </div>

            <div class="form-group">
              <label for="exampleFormControlFile1" class="thumbnail">Thumbnail</label>
              <div>
                <button type="button" class="btn video-button" @click="chooseImage">{{ imageName }}</button>
                <input style="display: none" ref="fileImageInput" type="file" accept="image/*" @change="onFileChange">
              </div>
              <div><img :src="image" class="img-fluid choose-image"/></div>
            </div>

            <div class="form-group">
              <label for="formGroupExampleInput" class="description">Description</label>
              <input type="text" class="form-control input-description" id="inlineFormInputGroupUsername1" placeholder="Please enter the description" v-model="description" >
            </div>

            <button type="submit" class="btn btn-primary button share-button" @click="share">Share</button>

          </form>
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
    formData: new FormData(),
    validateTitle: true,
    imageName: 'Choose Image',
    videoName: 'Choose Video'
    }
  },
  methods:{
    validationTitle: function() {
      this.validateTitle = (this.title ? true : false);
    },
    close() {
      this.$router.push(this.URL.urls.mainPath);
    },
    onVideoChange(e) {
      this.el = e;
      var files = e.target.files || e.dataTransfer.files;
      var videoType = /^video\//;
      if (!files.length){
        return;
      }
      if (!videoType.test(files[0].type)) {
        e.target.value = '';
        this.video = '';
        return;
      }
      this.createVideo(files[0]);
      this.formData.append('video', files[0]);
    },
    createVideo(file) {
      var reader = new FileReader();
      const that = this;
      reader.onload = (e) => {
        that.video = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    chooseImage() {
      this.$refs.fileImageInput.click();
    },
    chooseVideo() {
      this.$refs.fileVideoInput.click();
    },
    onFileChange(e) {
      this.el = e;
      var files = e.target.files || e.dataTransfer.files;
      var imageType = /^image\//;
      if (!files.length)
        return;
      if (!imageType.test(files[0].type)) {
        e.target.value = '';
        this.image = '';
        return;
      }
      this.createImage(files[0]);
      this.formData.append('image', files[0]);
    },
    createImage(file) {
      var reader = new FileReader();
      const that = this;
      reader.onload = (e) => {
        that.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    fileSelected(file){
      this.$emit('selected',file);
    },
    share() {
      this.formData.append('title', this.title);
      this.formData.append('description', this.description);
      this.$http.post(this.URL.urls.upload, this.formData)
      .then((response) => {
        this.$router.push(this.URL.urls.mainPath);
      }).catch((error) => console.log(error));
    }
  }
}
</script>
<style scoped>
.login {
  text-align: center;
  margin-top: 5%;
}

.share-button {
  margin-bottom: 2%;
}

.content {
  margin-top: 3%;
  width: 140%;
  border: 1px solid #8c8c8c;
  margin-bottom: 10%;
  background-color: #FFFFFF;
}

.video-button {
  margin-left: -75%;
  background-color: #3c8dbc;
  color: #FFFFFF
}

.header {
  border: 1px solid #cfceda;
}

h5 {
  margin-top: 2%;
}

.image-select {
  margin-left: 86%;
  margin-top: -11%;
}

.title {
  margin-left: -88%;
  margin-top: 3%;
}

.input_title {
  width: 91%;
  margin-left: 4%;
}

.video {
  margin-left: -85%;
  margin-top: 1%;
}

.video_file {
  margin-left: 4%;
}

.video_image {
  border: 1px solid;
  border-color: #797171;
  margin-left: 0%;
  width: 91%;
  margin-top: 2%;
  height: 250px
}

.choose-image {
  border: 1px solid;
  border-color: #797171;
  margin-left: 0%;
  width: 91%;
  margin-top: 2%;
  height: 200px
}

.thumbnail {
  margin-left: -80%;
}

.thumbnail_file {
  margin-left: 4%
}

.thumbnail_image {
  border: 1px solid;
  border-color: #797171;
  height: 163px;
  margin-left: 4%;
  width: 91%;
  margin-top: 2%;
}

#img1111 {
  width: 8%;
  float: left;
  position: absolute;
  top: 3%;
  right: -25%;
}

.image-thumbnail {
  border: 1px solid;
  border-color: #756969;
  /* height: 263px; */
  width: 91%;
  margin-left: 4%;
  margin-top: 4%
}

.description {
  margin-left: -74%;
}

.input-description {
  margin-left: 5%;
  width: 90%;
  height: 69px
}

.noneError {
  display: none;
}

.border {
  border: 1px solid #000;
}

.header h5{
  display: inline-block;
}
</style>
