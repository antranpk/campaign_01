<template>
    <div class="list-event-closed">
        <div class="container">
            <div>
                <div>
                    <div class="ui-block">
                        <table class="forums-table">
                            <thead>
                                <tr>
                                    <th class="forum title">
                                        <h6 class="result-campaign-h6 title">
                                            {{ $t('campaigns.title') }}
                                        </h6>
                                    </th>
                                    <th class="forum short">
                                        <h6 class="result-campaign-h6 title">
                                            {{ $t('campaigns.delete_at') }}
                                        </h6>
                                    </th>
                                    <th class="forum short">
                                        <h6 class="result-campaign-h6 title">
                                            {{ $t('campaigns.actions') }}
                                        </h6>
                                    </th>
                                    <th class="forum short" v-if="isManager">
                                        <h6 class="result-campaign-h6 title">
                                            {{ $t('campaigns.manage_events') }}
                                        </h6>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="event in eventsClosed.data">
                                    <td class="forum title">
                                        <div class="forum-item">
                                            <img :src="event.media[0].image_medium" v-if="event.media.length" alt="forum">
                                            <img :src="imageEventDefault" v-else alt="forum">
                                            <div class="content">
                                                <router-link
                                                    class="h6 notification-friend"
                                                    :to="{
                                                        name: 'event.index',
                                                        params: {
                                                            slug: event.campaign_id,
                                                            slugEvent: event.slug
                                                        }
                                                    }">
                                                    {{ event.title }}
                                                </router-link>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="short">
                                        <span class="h6 count">
                                            {{ deleteAt(event.deleted_at) }}
                                        </span>
                                    </td>
                                    <td class="short">
                                        <span class="h6 count">
                                            {{ event.actions_count }}
                                        </span>
                                    </td>
                                    <td class="short" v-if="isManager">
                                        <span class="notification-icon">
                                            <div class="togglebutton">
                                                <label>
                                                    <input
                                                        type="checkbox"
                                                        @change="confirmOpen"
                                                        :data-id="event.id">
                                                </label>
                                            </div>
                                        </span>
                                    </td>
                                </tr>
                                <tr v-if="!(eventsClosed.data.length)" class="no-events-closed">
                                    {{ $t('campaigns.no_events_are_closed') }}
                                </tr>
                            </tbody>
                        </table>
                        <div class="paginate">
                            <a
                                href="javascript:void(0)"
                                class="prev-page"
                                @click="prevPage"
                                v-if="eventsClosed.prev_page_url">
                                <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                                {{ $t('campaigns.prev_page') }}
                            </a>
                            <a
                                href="javascript:void(0)"
                                class="next-page"
                                @click="nextPage"
                                v-if="eventsClosed.next_page_url">
                                <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                                {{ $t('campaigns.next_page') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showConfirmOpen">
            <message :show.sync="show">
                <h5 class="exclamation-header" slot="header">
                    {{ $t('messages.comfirm_open_event') }}
                </h5>
                <div class="body-modal confirm-delete" slot="main">
                    <a href="javascript:void(0)"
                        class="btn btn-breez col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        @click="agreeOpen()">
                        {{ $t('form.button.agree') }}
                    </a>
                    <a href="javascript:void(0)"
                        class="btn btn-secondary col-lg-3 col-md-6 col-sm-12 col-xs-12"
                        @click="noOpen()">
                        {{ $t('form.button.no') }}
                    </a>
                </div>
            </message>
        </div>
    </div>
</template>

<script>
    import { get, patch } from '../../helpers/api'
    import Message from '../libs/Modal.vue'
    import axios from 'axios'

    export default {
        data: () => ({
            key: null,
            eventsClosed: {
                data : []
            },
            showConfirmOpen: false,
            idEvent: null,
            show: true,
            target: null,
            isManager: false,
            imageEventDefault: window.Laravel.settings.imageEventDefault
        }),

        created() {
            get(`campaign/events-closed/${this.pageId}`)
                .then(res => {
                    this.eventsClosed = res.data.eventsClosed
                    this.isManager = res.data.isManager
                })
                .catch(res => {

                })
        },

        methods: {
            deleteAt(time) {
                return moment(time, 'YYYY-MM-DD H:mm:ss').format('L');
            },

            confirmOpen(e) {
                this.target = $(e.currentTarget)
                this.idEvent = this.target.data('id')
                this.showConfirmOpen = true
            },

            agreeOpen() {
                patch(`event/open/${this.idEvent}`)
                    .then(res => {
                        this.showConfirmOpen = false
                        this.idEvent = null
                        this.target.prop('checked', true)
                        this.target.attr('disabled', true)
                        this.target = null
                    })
                    .catch(res => {

                    })
            },

            noOpen() {
                this.showConfirmOpen = false
                this.idEvent = null
                this.target.prop('checked', false)
                this.target = null
            },

            prevPage() {
                this.$Progress.start()
                axios({
                    method: 'GET',
                    url: this.eventsClosed.prev_page_url
                }).then(res => {
                    this.$Progress.finish()
                    this.eventsClosed = res.data.eventsClosed
                })
            },

            nextPage() {
                this.$Progress.start()
                axios({
                    method: 'GET',
                    url: this.eventsClosed.next_page_url
                }).then(res => {
                    this.$Progress.finish()
                    this.eventsClosed = res.data.eventsClosed
                })
            }
        },

        mounted() {
            $.material.init()
        },

        updated() {
            $.material.init()
        },

        components: {
            Message
        }
    }
</script>

<style lang="scss" scoped>
    .list-event-closed {
        .container {
            padding: 61px 0 20px;
            table {
                .short {
                    text-align: left;
                    padding: 15px 10px;
                }
                .title {
                    width: 65%;
                }
            }

            .paginate {
                height: 20px;
                padding: 0 5px;
                .prev-page {
                    float: left;
                }

                .next-page {
                    float: right;
                }
            }
            .no-events-closed {
                font-size: 2em;
                text-align: right;
            }
        }

        .togglebutton {
            margin-bottom: 0px;
        }
    }

    .check-join {
        margin: 4px 0px 0px;
        .fa {
            margin-left: 1px;
        }
    }

    .fa-spinner {
        font-size: 20px;
        margin-top: 1px;
        margin-right: 10px;
    }

    .hashtag {
        margin: 4px 0px;
    }

    .more {
        color: white;
    }

    .result-campaign-h6 {
        color: white;
        margin-bottom: 0px;
    }

    .mrg-top {
        margin-top: 20px;
    }

    .birthday-author-name {
        width: 50%;
    }

    .btn-choose {
        background: #38a9ff;
        margin-bottom: 0;
        float: right;
        padding: 0.5rem 1.5em;
        font-size: 11px;
        font-weight: bold;
        border-radius: 0.2rem;
        margin-top: 5px;
    }

    .cover-info {
        margin: 13px 0px;
        padding: 10px 0px;
        border: 1px solid #d9d9d9;
        .author-thumb {
            img {
                width: 50px !important;
                height: 50px !important;
            }
        }
        .h5 {
            font-size: 15px;
        }
        .col-md-5 {
            text-align: right;
            padding-top: 7%;
        }
    }

    .load-more {
        width: 100%;
        text-align: right;
        margin: auto 15px 10px;
        border-radius: 2px;
        padding: 15px;
        a {
            font-size: 15px;
            border: 1px solid #ffffff;
            padding: 6px 20px;
            background: #ffffff;
            border-radius: 4px;
            margin-right: 1%;
            color: #38a9ff;
            box-shadow: 0 1px 1px 0 rgba(0,0,0,.14), 0 2px 1px -1px rgba(0,0,0,.12), 0 1px 3px 0 rgba(0,0,0,.2);
            &:hover {
                text-decoration: underline;
                cursor: pointer;
                background: #79c2fa;
                color: white;
                border: 1px solid #79c2fa;
            }
        }
    }

    .btn-friend {
        padding: 5px 7px;
        border: 0px;
        color: white;
        border: 1px solid white;
        box-shadow: 0 1px 1px 0 rgba(0,0,0,.14), 0 2px 1px -1px rgba(0,0,0,.12), 0 1px 3px 0 rgba(0,0,0,.2);
        font-weight: bolder;
        background: #38a9ff;
    }

    .forums-table {
        width: 100%;
        border-radius: 5px;
        overflow: hidden;
    }

    .forums-table thead {
        background-color: #38a9ff;
    }

    .forums-table thead th {
        font-size: 12px;
        font-weight: 700;
        padding: 15px 25px;
        color: #fff;
        text-align: center;
    }

    .forums-table thead th.forum {
        text-align: left;
    }

    .forums-table td {
        padding: 0px 25px;
        text-align: center;
    }

    .forums-table td.forum {
        text-align: left;
    }

    .forums-table tr {
        border-bottom: 1px solid #e6ecf5;
        border-top: 1px solid #e6ecf5;
    }

    .forums-table tr:last-child {
        border-bottom: none;
    }

    .forums-table .count {
        font-size: 12px;
    }

    .forums-table .count:hover {
        color: #ff5e3a;
    }

    .forum-item img {
        float: left;
        margin-right: 15px;
        width: 90px;
        height: 90px;
    }

    .forum-item .content {
        overflow: hidden;
        padding-top: 30px;
    }

    .forum-item .title {
        color: ff5e3a;
    }

    .forum-item .title:hover {
        color: #3f4257;
    }

    .forum-item .text {
        margin-bottom: 0;
        font-size: 13px;
    }

    .forum-item .icon {
        font-size: 15px;
        color: #ffdc1b;
        margin-right: 10px;
        float: left;
    }

    .author-freshness .author-thumb {
        display: block;
        margin-bottom: 10px;
    }

    .author-freshness .author-thumb img {
        width: 40px;
        height: 40px;
    }

    .author-freshness .title {
        display: block;
        font-size: 12px;
        margin-bottom: 0;
    }

    .author-freshness .title:hover {
        color: #ff5e3a;
    }

    .author-freshness time {
        font-size: 11px;
    }

    .sub-forums {
        margin-bottom: 0;
        padding: 5px 0;
        margin-top: 15px;
        display: inline-block;
        border-left: 1px solid #e6ecf5;
    }

    .sub-forums a {
        font-size: 12px;
        padding: 5px 13px;
        display: block;
        margin-bottom: 0;
    }

    .sub-forums a:hover {
        color: #ff5e3a;
    }

    .author-started {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .author-started>*+* {
        margin-left: 8px;
    }

    .author-started span {
        font-size: 12px;
    }

    .author-started .title {
        margin-bottom: 0;
        font-size: 12px;
    }

    .author-started .author-thumb img {
        width: 18px;
        height: 18px;
        margin-right: 0;
    }

    /*------------- #W-FEATURED-TOPICS --------------*/

    .w-featured-topics li+li {
        margin-top: 20px;
    }

    .w-featured-topics .icon {
        float: left;
        margin-right: 10px;
        font-size: 15px;
        color: #ffdc1b;
    }

    .w-featured-topics .content {
        overflow: hidden;
    }

    .w-featured-topics .title {
        font-size: 13px;
        display: block;
        margin-bottom: 5px;
    }

    .w-featured-topics .title:hover {
        color: #ff5e3a;
    }

    .w-featured-topics time {
        font-size: 11px;
        margin-bottom: 5px;
        display: block;
    }

    .w-featured-topics .forums {
        font-size: 11px;
        color: #ff5e3a;
    }
</style>
