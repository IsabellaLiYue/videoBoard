<template>
  <div class="container">
    <div class="row">
      <div class="col-lg-2"></div>
      <div class="col-lg-6 login">
        <div class="content">
          <div class="header clearfix">
            <h5>Edit Video</h5>
            <div class="float-right"><router-link to="/main"><img src="../assets/BTN_Close_16x16.png" /></router-link></div>
          </div>

          <form>
            <div class="form-group">
              <label for="formGroupExampleInput" class="title">Title</label>
              <input type="text" class="form-control input_title" id="inlineFormInputGroupUsername" placeholder="Please enter the title" @blur="validationTitle" v-model="title" >
              <span class="text-danger input-text" :class="{noneError:validateTitle}">Video title cannot be blank</span>
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
              <input type="text" class="form-control input-description" id="inlineFormInputGroupUsername1" placeholder="Please enter the description" @blur="validationDescription" v-model="description" >
              <span class="text-danger input-text" :class="{noneError:validateDescription}">Video description cannot be blank</span>
            </div>
            <button type="submit" class="btn btn-primary button" @click="edit">Edit</button>

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
    validateDescription: true,
    imageName: 'Choose Image',
    }
  },
  created: function() {
    this.video = this.$route.query.video;
    this.title = this.video.title;
    this.description = this.video.description;
    this.image = this.video.thumbnail_path;
  },
  methods:{
    validationTitle: function() {
      this.validateTitle = (this.title ? true : false);
    },
    validationDescription: function() {
      this.validateDescription = (this.description ? true : false);
    },
    chooseImage() {
      this.$refs.fileImageInput.click();
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
      this.imageName = files[0].name;
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
    edit() {
      this.formData.append('title', this.title);
      this.formData.append('description', this.description);
      this.formData.append('videoId', this.video.id);
      this.$http.post(this.URL.urls.updateVideo, this.formData)
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

.content {
  margin-top: 3%;
  width: 140%;
  border: 1px solid #cfceda;
  margin-bottom: 10%;
  background-color: #FFFFFF;
}

.header {
  border: 1px solid #cfceda;
}

h5 {
  margin-top: 2%;
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
  margin-left: 4%;
  width: 91%;
  margin-top: 2%;
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

.imagede {
  width: 80%;
  margin-top: 2%
}

.header h5{
  display: inline-block;
}

.video-button {
  margin-left: -75%;
  background-color: #3c8dbc;
  color: #FFFFFF;
}

.choose-image {
  border-color: #797171;
  margin-left: 0%;
  width: 91%;
  margin-top: 2%;
  height: 200px
}
</style>
