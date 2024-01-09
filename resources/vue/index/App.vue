<script>
import Header from '../Header.vue';
import Footer from '../Footer.vue';
import CopyRight from '../CopyRight.vue';
import HowTo from '../HowTo.vue';

export default {
	components: {
		Header,
		Footer,
		CopyRight,
		HowTo,
	},
	data: () => ({
		form:{
			howto:{
				displayDialog: false,
			},
			copyright:{
				displayDialog: false,
			},
		},
	}),
};
</script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Rajdhani:wght@500&family=Shrikhand&display=swap');
</style>
<style lang="scss" scoped>
@import '../../scss/app.scss';
@import '../../scss/index.scss';
</style>

<template>
	<header>
		<Header></Header>
	</header>
	<div class="area">
		<div style="text-align: center;">
			<div style="margin:auto; max-width:400px; max-height:auto; position: relative; z-index:10;">
				<img src="image/top.png" style="width:100%; height:auto;" />
				<div class="btn-border-gradient-wrap btn-border-gradient-wrap--gold">
					<a 
					href="./room/" 
					style="width:360px;"
					class="btn btn-border-gradient">
						<span class="btn-text-gradient--gold">Play Game</span>
					</a>
				</div>
				<div style="margin:5px;"></div>
				<div class="btn-border-gradient-wrap btn-border-gradient-wrap--gold">
					<a 
					href="javascript:return(false);"
					style="width:360px;"
					class="btn btn-border-gradient" 
					@click="this.form.howto.displayDialog=true"
					>
						<span class="btn-text-gradient--gold">How To</span>
					</a>
				</div>
			</div>
		</div>
		<ul class="circles" style="z-index: 0;">
				<li>&yen;</li>
				<li>$</li>
				<li>€</li>
				<li>&yen;</li>
				<li>$</li>
				<li>€</li>
				<li>&yen;</li>
				<li>$</li>
				<li>€</li>
				<li>&yen;</li>
		</ul>
	</div>
	<!--HowToダイアログ-->
	<v-dialog 
	v-model="this.form.howto.displayDialog"
	transition="dialog-top-transition"
	max-width="400"
	>
		<v-card width="320" height="400">
			<v-card-text style="overflow-y: auto;">
				<div style="font-size:smaller;">
					<HowTo></HowTo>
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.howto.displayDialog = false;"
				>
					閉じる
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
	<!--Notifyダイアログ-->
	<v-dialog 
	v-model="this.form.copyright.displayDialog"
	transition="dialog-top-transition"
	max-width="400"
	>
		<v-card width="320" height="400">
			<v-card-text style="overflow-y: auto;">
				<div style="font-size:smaller;">
					<CopyRight></CopyRight>
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.copyright.displayDialog = false;"
				>
					閉じる
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>
	<footer @click="this.form.copyright.displayDialog = true;">
		<Footer></Footer>
	</footer>
</template>
