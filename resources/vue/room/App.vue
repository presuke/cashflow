<script>
export default {
	data: () => ({
		isLoading: true,
		rooms: [],
		tabs: [
			{ id: 1, name: 'Select', route: `/room/` },
			{ id: 2, name: 'Create', route: `/room/create/` },
		],
	}),
	mounted() {
		window.onload = () => {
		};
	},
	methods: {
		alert: function (msg) {
			alert(msg);
		},
		cancel() {
			console.log('parentEvent');
		},
		selectTab: function () {
			alert(this.tabIndex);
		},
	},
};
</script>
<style lang="scss">
.fade-enter-active {
	transition: all 0.5s ease-out;
	opacity: 0;
	transform: translateX(100px);
}
.fade-enter-to {
	opacity: 1;
	transform: translateX(0px);
}

.fade-leave-active {
	transition: all 0.5s ease-out;
	opacity: 1;
	transform: translateX(0px);
}
.fade-leave-to {
	opacity: 0;
	transform: translateX(-100px);
}

#app {
	font-family: Avenir, Helvetica, Arial, sans-serif;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
	color: #2c3e50;
}
</style>

<template>
	<v-container fluid>
		<Transition name="fade">
			<router-view></router-view>
		</Transition>
	</v-container>
</template>
