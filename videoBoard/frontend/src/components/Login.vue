<template>
  <div class="container">
    <div class="row">

      <div class="col-sm"></div>
      <div class="col-lg-6 login">
        <h1>Video Board</h1>
        <div class="content">
          <h5>Sign to start your session</h5>

          <form>
            <div class="form-group">
              <input type="text" class="form-control username" id="inlineFormInputGroupUsername" placeholder="Name" @blur="validationUserName" v-model="username">
              <span class="text-danger input-text" :class="{noneError:validateUserName}">User name cannot be blank</span>
            </div>

            <div class="form-group">
              <input type="password" class="form-control password" id="exampleInputPassword1" placeholder="Password" @blur="validationPassword" v-model="password">
              <span class="text-danger input-text" :class="{noneError:validatePassword}">Password cannot be blank</span>
              <span class="text-danger input-text">{{ message }}</span>
            </div>

            <div class="form-check check">
              <input type="checkbox" class="form-check-input" id="exampleCheck1" v-model="rememberMe">
              <label class="form-check-label" for="exampleCheck1">Remenber me</label>
            </div>

            <button type="submit" class="btn btn-primary button" @click="login">Login</button>

            <div class="div_hr">
              <span>OR</span>
            </div>

            <a href="#" class="badge badge-danger danger">Sigin in using Weibo</a>
          </form>

        </div>
      </div>
      <div class="col-sm">
      </div>
    </div>
  </div>
</template>
<script>
export default {
  data: () => {
    return {
      username: '',
      password: '',
      validateUserName: true,
      validatePassword: true,
      message: '',
      userId: '',
      rememberMe: '',
    }
  },
  created: function() {
    this.$http.get(this.URL.urls.isLogin)
      .then((response) => {
        if (response.data.code == "0") {
          this.$router.push(this.URL.urls.login);
        } else {
          this.$router.push({name: this.URL.urls.main, query: {user: response.data.user}});
        }
      }).catch((error) => console.log(error));
  },

  methods: {
    validationUserName: function() {
      this.validateUserName = (this.username ? true : false);
    },
    validationPassword: function() {
      this.validatePassword = (this.password ? true : false);
    },
    login: function() {
      this.message = "";
      let data = {'username': this.username, 'password': this.password, 'rememberMe': this.rememberMe}
      this.$http.post(this.URL.urls.userLogin, data)
      .then((response) => {
        if (response.data.code == "0") {
          this.message = response.data.msg;
        } else if (response.data.code == "-1") {
          this.message = response.data.msg;
        } else {
          this.userId = response.data;
          this.$router.push({name: this.URL.urls.main, query:{userId: this.userId }});
        }
      }).catch((error) => console.log(error));
    }
  }
}
</script>
<style scoped>
.noneError {
  display: none;
}

.login {
  text-align: center;
  margin-top: 4%;
}

.content {
  margin-top: 5%;
  width: 100%;
  height: 470px;
  border: 1px solid #8c8c8c;
  margin-bottom: 10%;
  background-color: #FFFFFF;
}

h5 {
  margin-top: 10%;
}

.username {
  margin: 0 auto;
  margin-top: 5%;
  width: 80%;
}

.password {
  margin: 0 auto;
  margin-top: 5%;
  width: 80%;
}

.check {
  text-align: left;
  width: 80%;
  margin: 0 auto;
}

.button {
  margin-top: 3%;
  width: 80%;
  height: 46px;
  background-color: #1776c3
}

.div_hr {
  margin: 15px;
}

.div_hr span {
  display: block;
  position: relative;
  color: #8c8c8c;
  text-align: center;
}

.div_hr span:before, .div_hr span:after {
  content: '';
  position: absolute;
  top: 50%;
  background: #8c8c8c;
  width: 33%;
  height: 1px;
}

.div_hr span:before{
  left: 7%;
}

.div_hr span:after {
  right: 7%;
}

.danger {
  width: 80%;
  height: 38px;
  font-size: 14px;
  line-height: 33px
}
</style>