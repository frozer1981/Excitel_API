import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import ExampleComponent from "./components/ExampleComponent.vue";

const app = createApp({});
app.component("example-component", ExampleComponent);
app.mount("#app");

createApp(App).mount('#app')
