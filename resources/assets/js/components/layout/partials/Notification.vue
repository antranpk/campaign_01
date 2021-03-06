<template>
    <!-- notification when user invited to join campaign -->
    <li :class="{ 'un-read': !notification.read_at }"
        @click="redirect(notification)"
        v-if="notification.type == 'App\\Notifications\\InviteUser'">
        <div class="author-thumb">
            <img :src="notification.data.from.image_thumbnail" alt="author">
        </div>
        <div class="notification-event">
            <div>
                <b>{{ notification.data.from.name }}</b>
                {{ $t('homepage.header.invited_join') }}
                <b class="notification-to">{{ notification.data.campaign.title }}</b>.
            </div>
            <span class="notification-date">
                <time class="entry-date updated">{{ timeAgo(notification.created_at) }}</time>
            </span>
        </div>
        <span class="notification-icon">
            <svg class="olymp-happy-face-icon">
                <use xlink:href="/frontend/icons/icons.svg#olymp-happy-face-icon"></use>
            </svg>
        </span>
    </li>

    <!-- notification when user invited to join campaign -->
    <li :class="{ 'un-read': !notification.read_at }"
        @click="redirect(notification)"
        v-else-if="notification.type == 'App\\Notifications\\UserDonate'">
        <div class="author-thumb">
            <img :src="notification.data.from.image_thumbnail" alt="author">
        </div>
        <div class="notification-event">
            <div>
                <b>{{ notification.data.from.name }}</b>
                {{ $t('homepage.header.user_donated') }}
                <b class="notification-to">{{ notification.data.event.title }}</b>.
            </div>
            <span class="notification-date">
                <time class="entry-date updated">{{ timeAgo(notification.created_at) }}</time>
            </span>
        </div>
        <span class="notification-icon">
            <i class="fa fa-gift" aria-hidden="true"></i>
        </span>
    </li>

    <!-- notification when owner event accept donation -->
    <li :class="{ 'un-read': !notification.read_at }"
        @click="redirect(notification)"
        v-else-if="notification.type == 'App\\Notifications\\AcceptDonation'">
        <div class="author-thumb">
            <img :src="notification.data.from.image_thumbnail" alt="author">
        </div>
        <div class="notification-event">
            <div>
                <b>{{ notification.data.from.name }}</b>
                {{ $t('homepage.header.accept_donate') }}
                <b class="notification-to">{{ notification.data.event.title }}</b>.
            </div>
            <span class="notification-date">
                <time class="entry-date updated">{{ timeAgo(notification.created_at) }}</time>
            </span>
        </div>
        <span class="notification-icon">
            <i class="fa fa-thumbs-o-up" aria-hidden="true"></i>
        </span>
    </li>

    <!-- notification when someone request to join campaign -->
    <li :class="{ 'un-read': !notification.read_at }"
        @click="redirect(notification)"
        v-else-if="notification.type == 'App\\Notifications\\UserRequest'">
        <div class="author-thumb">
            <img :src="notification.data.from.image_thumbnail" alt="author">
        </div>
        <div class="notification-event">
            <div>
                <b>{{ notification.data.from.name }}</b>
                {{ $t('homepage.header.request_join') }}
                <b class="notification-to">{{ notification.data.campaign.title }}</b>.
            </div>
            <span class="notification-date">
                <time class="entry-date updated">{{ timeAgo(notification.created_at) }}</time>
            </span>
        </div>
        <span class="notification-icon">
            <i class="fa fa-reply" aria-hidden="true"></i>
        </span>
    </li>

    <!-- notification when Owner campaign accept request's user -->
    <li :class="{ 'un-read': !notification.read_at }"
        @click="redirect(notification)"
        v-else-if="notification.type == 'App\\Notifications\\AcceptRequest'">
        <div class="author-thumb">
            <img :src="notification.data.from.image_thumbnail" alt="author">
        </div>
        <div class="notification-event">
            <div>
                <b>{{ notification.data.from.name }}</b>
                {{ $t('homepage.header.accept_request') }}
                <b class="notification-to">{{ notification.data.campaign.title }}</b>.
            </div>
            <span class="notification-date">
                <time class="entry-date updated">{{ timeAgo(notification.created_at) }}</time>
            </span>
        </div>
        <span class="notification-icon">
            <i class="fa fa-check-square-o" aria-hidden="true"></i>
        </span>
    </li>
</template>

<script>
    export default {
        props: [
            'notification',
            'totalUnreadNotifications',
            'show',
        ],

        methods: {
            timeAgo(time) {
                return moment(time, "YYYY-MM-DD h:mm:ss").fromNow()
            },
            redirect(notification) {
                switch (notification.type) {
                    case 'App\\Notifications\\InviteUser':
                    case 'App\\Notifications\\AcceptRequest':
                        this.$router.push({ name: 'campaign.timeline', params: {
                            slug: notification.data.campaign.slug
                        }})
                        break
                    case 'App\\Notifications\\UserRequest':
                        this.$router.push({ name: 'campaign.member_request', params: {
                            slug: notification.data.campaign.slug
                        }})
                        break
                    case 'App\\Notifications\\UserDonate':
                    case 'App\\Notifications\\AcceptDonation':
                        this.$router.push({ name: 'event.donation', params: {
                            slug: notification.data.event.campaign_id,
                            slugEvent: notification.data.event.slug
                        }})
                        break
                    default: this.$router.push({ name: 'homepage' })
                }

                this.$emit('update:totalUnreadNotifications', 0)
                this.$emit('update:show', false)
            },
        }
    }
</script>

<style lang="scss" scoped>
    .un-read {
        background: rgba(246, 247, 243, 0.82);
        &:hover {
            background: #fafbfd;
        }
    }

    i {
        font-size: 25px;
    }

    .fa-gift {
        color: rgb(121, 124, 148);
    }

    .fa-thumbs-o-up{
        color: #55c1b4;
    }

    .fa-reply {
        color: #5bc2ba;
    }

    .fa-check-square-o {
        color: #ffa58f;
    }

    .notification-to {
        color: #ff5e3a !important;
    }
</style>
