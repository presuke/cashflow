<script>
import Chart from 'chart.js/auto';
import Header from '../Header.vue';
import Action from './components/Action.vue'
import Score from './components/Score.vue'
import Footer from '../Footer.vue';

import seKatakata from '../../se/katakata.mp3';
import seCount from '../../se/count.mp3';
import seSlide from '../../se/slide.mp3';
import seError from '../../se/error.mp3';
import seSuccess from '../../se/success.mp3';
import seAction from '../../se/action.mp3';
import seDropLifelevel from '../../se/dropLifelevel.mp3';
import seRiseLifelevel from '../../se/riseLifelevel.mp3';
import seBuyAsset from '../../se/buyAsset.mp3';
import seTrade from '../../se/trade.mp3';
import seTreat from '../../se/treate.mp3';
import seWork from '../../se/work.mp3';
import seSic from '../../se/sic.mp3';
import seNoTrade from '../../se/noTrade.mp3';
import sePeriod from '../../se/period.mp3';
import seRiseFire from '../../se/riseFire.mp3';
import seDropFire from '../../se/dropFire.mp3';
import seWin from '../../se/win.mp3';
import { Bar, Pie } from 'vue-chartjs'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

export default {
	components: {
		Header,
		Action,
		Score,
		Footer,
		Bar,
		Pie,
	},
	data: () => ({
		playerid: 0,
		authtoken: '',
		isProcessing: false,
		room: {},
		players: [],
		assets: [],
		me: { 
			name: '',
		},
		action:{
			event: 0,
		},
		actionResult:{
			message: '',
			error: '',
		},
		crntPlayer:{},
		scores:{},
		errors: [],
		problem: -1,
		beforeStatus: '',
		const:{
			authTokenName: 'CF_AUTH_TOKEN',
			docPath: '',
			works:[],
			problems:{
				unKnown: -1,
				noProblem: 0,
				unAuth: 1,
				namelessMyself: 2,
				namelessOtherPlayer: 3,
			}
		},
		reflesh:{
			const:{
				interval: 1000,
				countMax: 5,
			},
			timer: '',
			count: 0,
			countValue: 40,
			message: '',
		},
		autoConfirm:{
			const:{
				startAt: 3000,
				interval: 1000,
				countMax: 10,
			},
			timer: '',
			count: 0,
		},
		form:{
			login:{
				displayDialog: false,
				pass: '',
				error: '',
				success: false,
			},
			banking:{
				displayDialog: false,
				amount: 0,
				target: 0,
				error:'',
				message: '',
				selection:[],
			},
			chart:{
				displayDialog: false,
				mode: 'datasTrans',
				datasTrans: {
					labels: [ 'January', 'February', 'March'],
					backgroundColor: ['#f00','#0f0'],
					datasets: [
						{
							label:'現金',
							type: "bar",
							backgroundColor: 'rgba(240,240,0,0.7)',
							data: [40, 20, 12],
						},
						{
							label:'株',
							type: "bar",
							backgroundColor: 'rgba(128,128,255,0.7)',
							data: [10, 15, 102],
						},
						{
							label:'不動産',
							type: "bar",
							backgroundColor: 'rgba(128,256,128,0.7)',
							data: [10, 15, 102],
						},
						{
							label:'ローン',
							type: "bar",
							backgroundColor: 'rgba(255,128,128,0.7)',
							data: [10, 15, 102],
						},
						{
							label:'総資産',
							type: "line",
							backgroundColor: 'rgba(128,128,128,0.7)',
							lineTension: 0.3,
					        fill: true,
							data: [10, 15, 102],
						},
					],
					options: {
						indexAxis: 'y',
						title:{
							text:'test',
						},
						responsive: true,
						scales: {
							x: {
								display: true,
								stacked: true,
								scaleLabel: {
									display: true,
									labelString: "Value"
								},
							},
							y: {
								display: true,
								stacked: true,
								scaleLabel: {
									display: true,
									labelString: "Value"
								},
							},
						},
					},
				},
				datasHistory: {
					labels: [ 'January', 'February', 'March'],
					backgroundColor: ['#f00','#0f0'],
					datasets: [
						{
							label:'行動',
							type: "radar",
							backgroundColor: 'rgba(204,204,255,0.7)',
							data: [40, 20, 12],
						},
					],
					options: {
						title:'test',
						responsive: true,
					},
					actions:[
						{name:'work', label:'労働'},
						{name:'buyEstate', label: '不動産投資'},
						{name:'buyStock', label: '株式投資'},
						{name:'trade', label: '資産売却'},
						{name:'dropLifeLevel', label: '生活水準降下'},
						{name:'sic', label: '静養'},
						{name:'treat', label: '休暇'},
						{name:'lostEstate', label: '不動産見送り'},
						{name:'lostStock', label: '株式見送り'},
						{name:'riseLifeLevel', label: '生活水準上昇'},
					],
				},
			},
			copyright:{
				displayDialog: false,
			},
		},
		se: {
			Katakata: null,
			Count:null,
			Slide: null,
			Error: null,
			Success: null,
			Action: null,
			DropLifelevel: null,
			RiseLifelevel: null,
			BuyAsset: null,
			Trade: null,
			Treat: null,
			Work: null,
			Sic: null,
			NoTrade: null,
			Period: null,
			RiseFire: null,
			DropFire: null,
			Win: null,
		},
		ret:{}
	}),
	created() {
		const segments = window.location.pathname.split('/');
		
		this.se.Katakata = new Audio(seKatakata);
		this.se.Count = new Audio(seCount);
		this.se.Slide = new Audio(seSlide);
		this.se.Error = new Audio(seError);
		this.se.Success = new Audio(seSuccess);
		this.se.Action = new Audio(seAction);
		this.se.DropLifelevel = new Audio(seDropLifelevel);
		this.se.RiseLifelevel = new Audio(seRiseLifelevel);
		this.se.BuyAsset = new Audio(seBuyAsset);
		this.se.Trade = new Audio(seTrade);
		this.se.Treat = new Audio(seTreat);
		this.se.Work = new Audio(seWork);
		this.se.Sic = new Audio(seSic);
		this.se.NoTrade = new Audio(seNoTrade);
		this.se.Period = new Audio(sePeriod);
		this.se.RiseFire = new Audio(seRiseFire);
		this.se.DropFire = new Audio(seDropFire);
		this.se.Win = new Audio(seWin);

		this.const.docPath = window.location.origin;
		for(let i=1; i<segments.length-2; i++){
			this.const.docPath += '/' + segments[i];
		}

		this.playerid = segments[segments.length - 1];
		this.problem = this.const.problems.unAuth;
		this.authtoken = localStorage.getItem(this.const.authTokenName);
		if(this.authtoken == null)
			this.authtoken = '';

		//銀行の借入額
		this.form.banking.selection = [];
		for(let i=500; i<=10000; i+=500){
			this.form.banking.selection.push(i);
		}

		//se
		this.se.count = new Audio(seKatakata);

		this.refleshStatus();
	},
	methods: {
		startRefleshTimer(){
			this.reflesh.count = 0;
			this.reflesh.timer = null;
			this.reflesh.timer = setInterval(()=>{
				this.reflesh.count++;
				this.reflesh.countValue = this.reflesh.count * 100 / this.reflesh.const.countMax;
				if(this.reflesh.count == this.reflesh.const.countMax){
					clearInterval(this.reflesh.timer);
					this.reflesh.timer = null;
					this.refleshStatus();
				}
			}, this.reflesh.const.interval);
		},
		startAutoConfirmTimer(){
			this.autoConfirm.count = this.autoConfirm.const.countMax;
			this.autoConfirm.timer = null;
			this.autoConfirm.timer = setInterval(()=>{
				this.autoConfirm.count--;
				this.actionResult.error = "";
				this.actionResult.message = this.autoConfirm.count + "秒後に自動で確認ボタンを押します。";
				if(this.autoConfirm.count == 0){
					this.confirm();
					this.reflesh.count = this.reflesh.const.countMax - 1;
				}
			}, this.autoConfirm.const.interval);
		},
		refleshStatus() {
			//部屋の状況を取得
			this.authtoken = localStorage.getItem(this.const.authTokenName);
			axios
			.get(this.const.docPath + '/api/v1/play/getRoomStatus', {
				params: {
					playerid: this.playerid,
					authtoken: this.authtoken,
				}
			})
			.then((response) => {
				try {
					if(response.data.code == 0){
						let isGameSet = false;
						this.problem = this.const.problems.noProblem;

						this.room = response.data.room;
						//プレイ画面更新
						this.players = [];
						for(let i=0; i< response.data.players.length; i++){
							let player = response.data.players[i];
							player.work = response.data.works[player.workid];

							player.stock = 0;
							player.estate = 0;
							player.loan = 0;
							for(let j=0; j< response.data.assets.length; j++){
								let asset = response.data.assets[j];
								if(asset.playerid == player.id && asset.has > 0){
									if(asset.type == 'stock'){
										player.stock += asset.buy * asset.has;
									}else if(asset.type == 'estate'){
										player.estate += asset.buy * asset.has;
									}else if(asset.type == 'loan'){
										player.loan += asset.buy;
									}
								}
							}

							if(player.flgFire == 2){
								isGameSet = true;
							}
							if(player.id == this.playerid){
								this.me = player;
							}else{
								this.players.push(player);
							}
						}
						this.players.unshift(this.me);

						this.assets = response.data.assets;
						this.crntPlayer = response.data.crntPlayer;
						this.action = response.data.action;

						//カードめくる時になったらメッセージ削除
						if(this.action.event == 0){
							this.actionResult.message = '';
							this.actionResult.error = '';
						}

						//音を鳴らす
						this.playSoundErrect();

						//スコアを更新する
						this.updateScore();

						//自分がプレイヤーで資産購入・売却であれば、自動ページ更新処理を起動しない
						if((this.action.event == 2 || this.action.event == 3 || this.action.event == 4) && this.crntPlayer.id == this.me.id){
							this.reflesh.count = 0;
						}else if(isGameSet){
							this.reflesh.count = 0;
						}else{
							this.startRefleshTimer();

							if(this.action.event == 99){
								let flgAutoConfirm = !(this.autoConfirm.timer > 0);
								//自動確認
								try{
									const  myHistory = response.data.myHistory;
									//カレントプレイヤーに対して確認済みかどうか
									if(myHistory.action == 'confirm'){
										const parameter = JSON.parse(myHistory.parameter);
										if(parameter.crntPlayerid == this.crntPlayer.id){
											flgAutoConfirm = false;
										}
									}

									if(flgAutoConfirm){
										setTimeout(()=>{
											this.startAutoConfirmTimer();
										}, this.autoConfirm.const.startAt);
									}
								}catch(e){
								}
							}
						}

						//確認ボタンが表示されていないなら自動確認昨日はOFFにする。
						if(this.action.event != 99 && this.autoConfirm.timer > 0){
							clearInterval(this.autoConfirm.timer);
							this.autoConfirm.timer = null;
							this.actionResult.message = "";
						}

					}else if(response.data.code == 7){
						//名無しエラー
						this.problem = this.const.problems.namelessOtherPlayer;
					}else if(response.data.code == 8){
						//名無しエラー
						this.problem = this.const.problems.namelessMyself;
					}else if(response.data.code == 1 || response.data.code == 9){
						this.form.login.displayDialog = true;
					}else if(response.data.room  != undefined){
						this.rooms = response.data.room;
					}else if(response.data.errors.length > 0){
						this.errors = response.data.errors;
						console.log(this.errors);
					}else{
						this.errors.push('特定できないエラー');
					}
				} catch (e) {
					this.errors = e;
				}
			})
			.catch((err) => {
				console.log(err);
				setTimeout(() =>this.refleshStatus(), 500);
			});
		},
		playSoundErrect(){
			const key = this.crntPlayer.id + ':' + this.action.action;
			if(this.beforeStatus != key){
				this.beforeStatus = key;
				switch(this.action.action){
					case 'work':{
						this.se.Work.play();
						break;
					}
					case 'sic':{
						this.se.Sic.play();
						break;
					}
					case 'treat':{
						this.se.Treat.play();
						break;
					}
					case 'dropLifeLevel':{
						this.se.DropLifelevel.play();
						break;
					}
					case 'riseLifeLevel':{
						this.se.RiseLifelevel.play();
						break;
					}
					case 'trade':{
						this.se.Trade.play();
						break;
					}
					case 'buyEstate':{
						this.se.BuyAsset.play();
						break;
					}
					case 'buyStock':{
						this.se.BuyAsset.play();
						break;
					}
					case 'lostEstate':{
						this.se.NoTrade.play();
						break;
					}
					case 'lostStock':{
						this.se.NoTrade.play();
						break;
					}
					case 'riseZone':{
						this.se.RiseFire.play();
						break;
					}
					case 'dropZone':{
						this.se.DropFire.play();
						break;
					}
					case 'win':{
						this.se.Win.play();
						break;
					}
					default:{
						if(this.crntPlayer.turn % this.room.period == 0){
							this.se.Period.play();
						}else{
							this.se.Slide.play();
						}
						break;
					}
				}
			}
		},
		updateScore(){
			try{
				const keyParameters = ['money', 'stock', 'estate', 'loan', 'stress'];
				//初呼び出しの場合は初期化
				if(JSON.stringify(this.scores) == '{}'){
					this.players.forEach(player => {
						let key = player.id + '';
						this.scores[key] = {};
						keyParameters.forEach(keyParameter =>{
							this.scores[key][keyParameter] = 0;
						});
					});
				}

				this.players.forEach(player => {
					let endCount = {};
					keyParameters.forEach(keyParameter =>{
						endCount[keyParameter] = 0;
						if(player[keyParameter] != this.scores[player.id][keyParameter]){
							let timerAnimation = setInterval(() =>{
								if(Math.abs(this.scores[player.id][keyParameter] - player[keyParameter]) < 10
								   || endCount[keyParameter] > 10){
									this.scores[player.id][keyParameter] = player[keyParameter];
									clearInterval(timerAnimation);
									this.se.Katakata.pause();
							        this.se.Katakata.currentTime = 0;
								}else{
									this.scores[player.id][keyParameter] += Math.ceil((player[keyParameter] - this.scores[player.id][keyParameter]) / 5);
									this.se.Katakata.play();
									endCount[keyParameter]++;
								}
							},100);
						}
					});
				});
			}catch(err)
			{
				console.log(err);
			}
		},
		act(actionMode){
			if(this.crntPlayer.id != this.me.id){
				this.actionResult.error = 'あなたのターンではありません。';
				this.se.Error.play();
				return;
			}
			this.se.Action.play();
			this.isProcessing = true;
			this.reflesh.count = 0;
			this.actionResult.error = '';
			this.actionResult.message = '';
			this.ret = '';
			this.authtoken = localStorage.getItem(this.const.authTokenName);
			let params = {};
			params.playerid = this.playerid;
			params.authtoken = this.authtoken;
			params.actionMode = actionMode;
			if(actionMode != 'drowCard'){
				params.assets = this.assets;
			}

			axios
			.post(this.const.docPath + '/api/v1/play/action', {
				params,
			})
			.then((response) => {
				try {
					this.isProcessing = false;
					//this.action.event = actionEventBackup;
					this.reflesh.count = this.reflesh.const.countMax - 1;
					if(response.data.error != undefined) {
						this.actionResult.error = response.data.error;
						this.refleshStatus();
					}else if(response.data.message != undefined){
						this.actionResult.message = response.data.message;
						this.refleshStatus();
					}
				} catch (e) {
					this.errors = e;
				}
			})
			.catch((err) => {
				this.ret = [];
				this.ret.push(err);
			});
		},
		confirm(){
			this.actionResult.error = '';
			this.se.Action.play();
			clearInterval(this.autoConfirm.timer);
			this.autoConfirm.timer = null;

			axios
			.post(this.const.docPath + '/api/v1/play/action', {
				params: {
					playerid: this.playerid,
					crntPlayer: this.crntPlayer,
					authtoken: this.authtoken,
					actionMode: 'confirm',
				}
			})
			.then((response) => {
				try {
					if(response.data.error != undefined) {
						this.actionResult.error = response.data.error;
					}else if(response.data.message != undefined){
						this.actionResult.message = response.data.message;
						this.reflesh.count * 100 / this.reflesh.const.countMax - 1;
					}
				} catch (e) {
					this.errors = e;
				}
			})
			.catch((err) => {
				console.log(err);
			});
		},
		doBanking(target){
			if(this.crntPlayer.id != this.me.id){
				this.actionResult.error = 'あなたのターンではありません。';
				this.se.Error.play();
				return;
			}
			this.se.Action.play();
			if(target > 0){
				this.form.banking.target = target;
				this.form.banking.amount = 0;
				this.form.banking.error = '';
				this.form.banking.message = '';
				this.form.banking.displayDialog = true;
			}else if(target == 0){
				this.form.banking.error = '';
				this.form.banking.message = '';
				if(this.form.banking.amount == 0){
					this.form.banking.error = '借入額を決めてください。';
					this.se.Error.play();
				}else if(this.form.banking.target > this.crntPlayer.money + this.form.banking.amount){
					this.form.banking.error = this.form.banking.amount.toLocaleString() + '借入れても物件価格に到達しません。';
					this.se.Error.play();
				}else{
					axios
					.post(this.const.docPath + '/api/v1/play/action', {
						params: {
							playerid: this.playerid,
							authtoken: this.authtoken,
							actionMode: 'banking',
							amount: this.form.banking.amount,
						}
					})
					.then((response) => {
						try {
							if(response.data.error != undefined){
								this.form.banking.error = response.data.error;
								this.se.Error.play();
							}else{
								this.form.banking.message = response.data.message;
								this.refleshStatus();
								this.se.Success.play();
								this.form.banking.displayDialog = false;
							}
						} catch (e) {
							this.errors = e;
						}
					})
					.catch((err) => {
						console.log(err);
					});
				}
			}
		},
		viewTrans(player){
			axios
			.get(this.const.docPath + '/api/v1/play/getTrans', {
				params:{
					myid: this.me.id,
					playerid: player.id,
					authtoken: this.authtoken,
				}
			})
			.then((response) => {
				try {
					this.form.chart.datasTrans.labels = [];
					this.form.chart.datasTrans.datasets[0].data = [];
					this.form.chart.datasTrans.datasets[1].data = [];
					this.form.chart.datasTrans.datasets[2].data = [];
					this.form.chart.datasTrans.datasets[3].data = [];
					this.form.chart.datasTrans.datasets[4].data = [];
					response.data.trans.forEach((trans) => {
						this.form.chart.datasTrans.labels.push(trans.turn);
						this.form.chart.datasTrans.datasets[0].data.push(trans.money);
						this.form.chart.datasTrans.datasets[1].data.push(trans.stock);
						this.form.chart.datasTrans.datasets[2].data.push(trans.estate);
						this.form.chart.datasTrans.datasets[3].data.push(trans.loan * -0.1);
						this.form.chart.datasTrans.datasets[4].data.push(trans.money + trans.stock + trans.estate - (trans.loan * 0.1));
					});

					this.form.chart.datasHistory.labels = [];
					this.form.chart.datasHistory.datasets[0].data = [];
					this.form.chart.datasHistory.actions.forEach((action) => {
						let val = 0;
						response.data.history.forEach((history) => {
							if(action.name == history.action){
								val = history.cnt;
							}
						});
						this.form.chart.datasHistory.labels.push(action.label);
						this.form.chart.datasHistory.datasets[0].data.push(val);
					});
					this.form.chart.mode = 'trans';
					this.form.chart.displayDialog = true;
				} catch (e) {
					this.errors = e;
				}
			})
			.catch((err) => {
				console.log(err);
			});
		},
		login(){
			this.form.login.error = '';
			this.se.Action.play();

			axios
			.post(this.const.docPath + '/api/v1/play/login', {
				playerid: this.playerid,
				pass: this.form.login.pass,
			})
			.then((response) => {
				try {
					if(response.data.code == 0 && response.data.token != ''){
						this.authtoken = response.data.token;
						localStorage.setItem(this.const.authTokenName, response.data.token);
						this.form.login.success = true;
						this.form.login.displayDialog = false;
						this.refleshStatus();
						this.se.Success.play();
					}
					else if(response.data.error != undefined){
						this.form.login.displayDialog = true;
						this.form.login.error = response.data.error;
						this.se.Error.play();
					}else{
						this.errors.push('特定できないエラー');
						this.se.Error.play();
					}
				} catch (e) {
					this.errors = e;
				}
			})
			.catch((err) => {
				console.log(err);
			});
		},
		logout(){
			localStorage.setItem(this.const.authTokenName, '');
			this.refleshStatus();
			this.se.Action.play();
		},
		changeStockHas(stock, num){
			this.se.Count.play();

			if(num > 0 || stock.has != 0){
				stock.has += num;
			}
			let assetTotal = 0;
			this.assets.forEach((asset)=>{
				if(asset.playerid == this.crntPlayer.id &&
				   asset.turn == this.crntPlayer.turn && 
				   asset.type=='stock'){
					assetTotal += asset.buy * asset.has;
				}
			})
			if(assetTotal > this.me.money){
				this.actionResult.error = '所持金が足りません。合計金額：' + assetTotal.toLocaleString();
			}else{
				this.actionResult.error = '';
			}
		},
		checkString: function (inputdata) {
			var regExp = /^[a-zA-Z0-9_]*$/;
			return regExp.test(inputdata);
		},
		checkMaxLength: function (inputdata) {
			var booleanLength = false;
			inputdata.length <= 15
				? (booleanLength = true)
				: (booleanLength = false);
			return booleanLength;
		},
	},
};
</script>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Rajdhani:wght@500&family=Shrikhand&display=swap');
</style>
<style lang="scss" scoped>
@import '../../scss/app.scss';
</style>

<template>
	<header>
		<Header></Header>
	</header>
	<div class="area">
		<div id="action">
			<Action></Action>
		</div>
		<div id="score">
			<Score></Score>
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
		{{ this.errors }}
		<ul class="error">
			<li v-for="error in this.errors">{{ error }}</li>
		</ul>
	</div>

	<!-- ログインダイアログ-->
	<v-dialog
	v-model="this.form.login.displayDialog"
	transition="dialog-top-transition"
	max-width="400"
	class="dialog"
	>
		<v-card>
			<v-card-title
			color="primary"
			dark
			>
			<span class="text-h5">認証</span>
			</v-card-title>
			<v-card-text>
				<v-container>
					<v-row>
						<v-col cols="12">
							<v-text-field
							label="Password*"
							type="password"
							v-model="this.form.login.pass"
							required
							></v-text-field>
						</v-col>
					</v-row>
					<v-row>
						<div v-for="n in 10" style="float:left; margin:5px;">
							<v-btn @click="this.form.login.pass += (n%10) + ''">{{ n%10 }}</v-btn>
						</div>
						<div style="float:left; margin:5px;">
							<v-btn @click="this.form.login.pass = ''">Clear</v-btn>
						</div>
					</v-row>
				</v-container>
				<div class="error">
					{{this.form.login.error}}
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="login()"
				>
					ログイン
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<!-- グラフ表示ダイアログ-->
	<v-dialog
	v-model="this.form.chart.displayDialog"
	transition="dialog-top-transition"
	max-width="720"
	Width="90%"
	Height="500"
	class="dialog"
	style="overflow-y: scroll;"
	>
		<v-card>
			<v-card-title
			color="primary"
			dark
			>
			<span class="text-h5">実績グラフ</span>
			</v-card-title>
			<v-card-text>
				<div style="width:100%;height:70vh;">
					<Bar
					style="width:100%;height:100%;"
					v-bind:class="[this.form.chart.mode == 'trans' ? 'scaleShow' : 'scaleHide']" 
					:options="this.form.chart.datasTrans.options"
					:data="this.form.chart.datasTrans"
					/>
					<Bar
					style="width:100%;height:100%;"
					v-bind:class="[this.form.chart.mode == 'history' ? 'scaleShow' : 'scaleHide']" 
					:options="this.form.chart.datasHistory.options"
					:data="this.form.chart.datasHistory"
					/>
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.chart.mode = 'trans';"
				>
					資産推移
				</v-btn>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.chart.mode = 'history';"
				>
					行動分析
				</v-btn>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.chart.displayDialog = false;"
				>
					閉じる
				</v-btn>
			</v-card-actions>
		</v-card>
	</v-dialog>

	<!--銀行借り入れダイアログ-->
	<v-dialog 
	v-model="this.form.banking.displayDialog"
	transition="dialog-top-transition"
	max-width="400"
	class="dialog"
	>
		<v-card width="320" height="400">
			<v-card-title
			color="primary"
			dark
			>
				<span class="text-h5">借入</span>
			</v-card-title>
			<v-card-text>
				<div>{{ this.form.banking.target.toLocaleString() }}の物件を検討中。</div>
				<div>現在の金利は{{ room.interest }}％です。</div>
				<v-container>
					<v-row>
						<v-col cols="12">
							<v-select
								label="借入額"
								v-model="form.banking.amount"
								:items="form.banking.selection"
							></v-select>
						</v-col>
					</v-row>
				</v-container>
				<div class="error">
					{{ form.banking.error }}
				</div>
				<div class="message">
					{{ form.banking.message }}
				</div>
				<div style="margin-top:5px; border-radius:5px; background-color:lightyellow;color:red; font-size:smaller;">
					<div>*借入限度は給料の10倍です。</div>
					<div>*元金均等法10分割で、決算時に返済します。</div>
				</div>
			</v-card-text>
			<v-card-actions>
				<v-spacer></v-spacer>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="this.form.banking.displayDialog = false"
				>
					やめる
				</v-btn>
				<v-btn
				color="blue-darken-1"
				variant="text"
				@click="doBanking(0)"
				>
					借入れる
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
				@click="this.logout();"
				>
					ログアウト
				</v-btn>
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
