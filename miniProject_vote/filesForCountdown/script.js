const year = new Date().getFullYear();
const month = new Date().getMonth();
const date = new Date().getDate();
const deadline = new Date(year,month,date,18);
// countdown
let timer = setInterval(function() {

  // get today's date
  const today = new Date();
  // const today=new Date();

  // get the difference
  const diff = deadline - today;
  console.log(diff);
  if(diff<0){
    clearInterval(timer);
  }
  // math
  let days = Math.floor(diff / (1000 * 60 * 60 * 24));
  let hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  let minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  let seconds = Math.floor((diff % (1000 * 60)) / 1000);

  // let days=today.getDate();
  // let hours=today.getHours();
  // let minutes=today.getMinutes();
  // let seconds=today.getSeconds();

  // display
  document.getElementById("timer").innerHTML =
    "<div class=\"days\"> \
  <div class=\"numbers\">" + days + "</div>days</div> \
<div class=\"hours\"> \
  <div class=\"numbers\">" + hours + "</div>hours</div> \
<div class=\"minutes\"> \
  <div class=\"numbers\">" + minutes + "</div>minutes</div> \
<div class=\"seconds\"> \
  <div class=\"numbers\">" + seconds + "</div>seconds</div> \
</div>";

}, 1000);