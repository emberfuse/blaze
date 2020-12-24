import Welcome from "@/Views/Marketing/Welcome";
import Login from "@/Views/Auth/Login";
import Register from "@/Views/Auth/Register";
import Home from "@/Views/Business/Home";

import auth from "./middleware/auth";
import guest from "./middleware/guest";

export default {
    mode: "history",

    routes: [
        {
            path: "/",
            name: "welcome",
            component: Welcome
        },
        {
            path: "/login",
            name: "login",
            component: Login,
            beforeEnter: guest
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            beforeEnter: guest
        },
        {
            path: "/home",
            name: "home",
            component: Home,
            beforeEnter: auth
        }
    ]
};
