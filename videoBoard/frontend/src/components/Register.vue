<template>
  <div class="container">
    <div class="row">

      <div class="col-sm"></div>
      <div class="col-lg-6 login">
        <h1>Video Board</h1>
        <div class="content">
          <h5>Register a new membership</h5>

          <form>
            <div class="form-group">
              <input type="text" class="form-control username" id="inlineFormInputGroupUsername" placeholder="Name" @blur="validationUserName" v-model="username">
              <span class="text-danger input-text" :class="{noneError:validateUserName}">User name cannot be blank</span>
            </div>

            <div class="form-group">
              <input type="password" class="form-control password" id="exampleInputPassword1" placeholder="Password" @blur="validationPassword" v-model="password">
              <span class="text-danger input-text" :class="{noneError:validatePassword}">Password cannot be blank</span>
            </div>

            <div class="form-group">
              <input type="password" class="form-control password" id="exampleInputRetypePassword" placeholder="Retype password" @blur="validationRePassword" v-model="rePassword">
              <span class="text-danger input-text" :class="{noneError:validateRePassword}">Retype password cannot be blank</span>
              <span class="text-danger input-text" :class="{noneError:validateSamePassword}">password is different from retype password</span>
              <span class="text-danger input-text">{{ message }}</span>
            </div>

            <button type="submit" class="btn btn-primary button" @click="register">Register</button>

            <div class="div_hr"><span>OR</span></div>

            <a href="#" class="badge badge-danger danger">Sigin in using Weibo</a>
          </form>

          <nav class="nav justify-content-center login-link">
            <a class="nav-link active" href="#"><router-link to="/Login">I already have a membership</router-link></a>
          </nav>

        </div>
      </div>
      <div class="col-sm"></div>
    </div>
  </div>
</template>
<script>
export default {
  data: () => {
    return {
      username: '',
      password: '',
      rePassword: '',
      validateUserName: true,
      validatePassword: true,
      validateRePassword: true,
      validateSamePassword: true,
      message: ''
    }
  },
  methods: {
    validationUserName: function() {
      this.validateUserName = (this.username ? true : false);
    },
    validationPassword: function() {
      this.validatePassword = (this.password ? true : false);
    },
    validationRePassword: function() {
      this.validateRePassword = (this.rePassword ? true : false);
    },
    register: function() {
      if (this.password != this.rePassword) {
        this.validateSamePassword = false;
      } else {
        let data = {'username': this.username, 'password': this.password}
        this.$http.post(this.URL.urls.registerUrl, data)
        .then((response) => {
          if (response.data.code == "0") {
            this.message = response.data.msg;
            this.message = "";
          } else if (response.data.code == "-1") {
            this.message = response.data.msg;
            this.message = "";
          } else {
            this.$router.push(this.URL.urls.login);
          }
        }).catch((error) => console.log(error));
      }
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
  margin-top: 3%;
}

.content {
  margin-top: 2%;
  width: 100%;
  margin-bottom: 10%;
  border: 1px solid #8c8c8c;
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

.login-link {
  margin-top: 2%;
}
</style>