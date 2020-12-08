<template>
    <div>
        <div class="wrapper wrapper-full-page">
            <div class="full-page" data-color="azure"
                 data-image="storage/static/img/background/background-2.jpg">
                <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
                <div class="content">
                    <div class="container">
                        <div class="row text-center title-login-div">
                            <router-link class="title-login" to="/login">Southern Staircase</router-link>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-md-offset-3 col-sm-offset-3">
                                <form method="post" action="javascript:void(0)">
                                    <div class="card" data-background="color" data-color="blue">
                                        <div class="card-header">
                                            <h3 class="card-title">Register a New Account</h3>
                                        </div>
                                        <div class="card-content">
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" placeholder="Enter First Name" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" placeholder="Enter Last Name" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>Company/Organization</label>
                                                <input type="text" placeholder="Enter Company Name" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>User Name </label>
                                                <input type="text" placeholder="Enter User Name" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>Email </label>
                                                <input type="email" placeholder="Enter Email" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>Password</label>
                                                <input type="password" placeholder="Password" class="form-control input-no-border">
                                            </div>

                                            <div class="form-group">
                                                <label>Retype Password</label>
                                                <input type="password" placeholder="Retype Password" class="form-control input-no-border">
                                            </div>
                                        </div>

                                        <div class="card-footer text-center">
                                            <button type="submit" class="btn btn-fill btn-wd ">Register</button>
                                            <div class="forgot">
                                                <router-link to="/login">Login Here!</router-link>
                                            </div>
                                            <div class="forgot">
                                                <a href="javascript:void(0)">Forgot your password?</a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <footer class="footer footer-transparent">
                    <div class="container">
                        <div class="copyright">
                            Copyright &copy; 2018 Artistic Stairs | Powered by Cloudly
                        </div>

                        <!--<button v-tooltip="{
                          content: 'hover on it',
                          placement: 'bottom-center',
                          classes: ['info'],
                          targetClasses: ['it-has-a-tooltip'],
                          offset: 10,
                          delay: {
                            show: 300,
                            hide: 500000,
                          },
                        }">Hover me</button>-->

                    </div>
                </footer>
                <div class="full-page-background" style="background-image: url(storage/static/img/background/background-2.jpg) "></div>
            </div>
        </div>
    </div>
</template>

<script>


    export default {

    }
</script>

<style scoped>
    .title-login-div {
        margin: 25px 0;
        font-size: 25px;
        color: #fff !important;
        font-weight: 600;
    }
    .title-login{
        color: #fff;
    }
    .forgot{
        margin-top: 10px;
    }
</style>
