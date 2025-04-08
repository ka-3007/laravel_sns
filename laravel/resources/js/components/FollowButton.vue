<template>
    <div>
        <button class="btn-sm shadow-none border border-primary p-2" :class="buttonColor" @click="clickFollow">
            <i class="mr-1" :class="buttonIcon"></i>
            {{ buttonText }}
        </button>
    </div>
</template>

<script>
export default {
    props: {
        initialIsFollowedBy: {
            type: Boolean,
            default: false,
        },
        authorized: {
            type: Boolean,
            default: false,
        },
        endpoint: {
            type: String,
        },
    },
    data() {
        return {
            isFollowedBy: this.initialIsFollowedBy,
        }
    },
    computed: {
        buttonColor() {
            return this.isFollowedBy
                ? 'bg-primary text-white'
                : 'bg-white'
        },
        buttonIcon() {
            return this.isFollowedBy
                ? 'fas fa-user-check'
                : 'fas fa-user-plus'
        },
        buttonText() {
            return this.isFollowedBy
                ? 'フォロー中'
                : 'フォロー'
        },
    },
    methods: {
        clickFollow() {
            if (!this.authorized) {
                return
            }

            this.isFollowedBy
                ? this.unfollow()
                : this.follow()
        },
        async follow() {
            const response = await axios.put(this.endpoint)

            this.isFollowedBy = true
        },
        async unfollow() {
            const response = await axios.delete(this.endpoint)

            this.isFollowedBy = false
        },

        updateFollowCounts(isFollowing) {
            const followersCountElement = document.getElementById('followers-count');
            let currentFollowers = parseInt(followersCountElement.textContent, 10);

            if (isFollowing) {
                followersCountElement.textContent = currentFollowers + 1;
            } else {
                followersCountElement.textContent = currentFollowers - 1;
            }
        }
    },
    watch: {
        isFollowedBy(isFollowing) {
            this.updateFollowCounts(isFollowing);
        }
    }
}
</script>
