<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap");

*,
*:before,
*:after {
    box-sizing: border-box;
    outline: none !important;
}

body,
html {
    margin: 0;
    width: 100%;
    height: 100%;
}

body {
    overflow-x: hidden;
    font-family: "Poppins", sans-serif;
    color: #fff;
    font-size: 13px;
    background-color: #141417;
}

ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}

a {
    text-decoration: none;
    color: inherit;
}

button {
    font-family: inherit;
}

button,
button:active,
button:focus {
    outline: none;
    border: none;
}

#app {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    padding: 0 40px;
    transition: padding 0.2s, padding-bottom 0s;
}
</style>

<template>
    <Topbar/>
    <Content>
        <MainContainer/>
        <MapViewer/>
    </Content>
    <Footer/>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import MainContainer from "./components/MainContainer.vue";
import MapViewer from "./components/MapViewer.vue";
import Topbar from "./layout/Topbar.vue";
import Content from "./layout/Content.vue";
import Footer from "./layout/Footer.vue";

export default defineComponent({
    name: "App",
    components: { MainContainer, MapViewer, Topbar, Content, Footer },
});
</script>