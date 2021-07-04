import axios from "axios";

let Api = axios.create({
  baseURL: "http://localhost:8001/api"
});

Api.defaults.withCredentials = true;

export default Api;