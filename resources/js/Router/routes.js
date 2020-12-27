import Welcome from "@/Views/Marketing/Welcome";
import Login from "@/Views/Auth/Login";
import Register from "@/Views/Auth/Register";
import Home from "@/Views/Business/Home";
import Test from "@/Views/Testing/Test";

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
            meta: { guest: true }
        },
        {
            path: "/register",
            name: "register",
            component: Register,
            meta: { guest: true }
        },
        {
            path: "/home",
            name: "home",
            component: Home,
            meta: { auth: true }
        },
        {
            path: "/test",
            name: "test",
            component: Test
        }
    ]
};
