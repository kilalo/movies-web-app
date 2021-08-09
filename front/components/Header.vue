<template>
    <div>
        <b-navbar toggleable="lg" type="dark" variant="dark">
            <b-container>
                <b-navbar-brand class="text-2xl">
                    <b-icon-app-indicator></b-icon-app-indicator>
                    Movies web app
                </b-navbar-brand>
                <b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
                <b-collapse id="nav-collapse" is-nav>
                <b-navbar-nav>
                    <NuxtLink to="/" class="text-light m-3">
                        <b-icon-house-door-fill></b-icon-house-door-fill>
                        Home
                    </NuxtLink>
                    <NuxtLink to="/movies" class="text-light m-3">
                        <b-icon-film></b-icon-film>
                        Movies
                    </NuxtLink>
                </b-navbar-nav>

                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                </b-navbar-nav>
                </b-collapse>
            </b-container>
        </b-navbar>
    </div>
</template>
