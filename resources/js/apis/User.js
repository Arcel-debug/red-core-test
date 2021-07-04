import Api from "./Api";
import Csrf from "./Csrf";

export default {
  async register(form) {
    await Csrf.getCookie();

    return Api.post("/user/register", form);
  },

  async login(form) {
    await Csrf.getCookie();

    return Api.post("/user/login", form);
  },

  async logout() {
    await Csrf.getCookie();

    return Api.post("/user/logout");
  },

  auth() {
    // return Api.get("/user/auth_check");
    // console.log(sessionStorage.getItem('auth'))
    if (sessionStorage.getItem('auth') && sessionStorage.getItem('auth') == '1') {
      return true;
    } else {
      return false;
    }
  }
};