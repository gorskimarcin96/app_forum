<template>
  <div>
    <span>{{ date }} | {{ time }}</span>
  </div>
</template>

<script>
export default {
  data() {
    return {
      time: '',
      date: '',
      interval: null
    }
  },
  mounted() {
    this.setTime()
    this.setDate();
    this.interval = setInterval(() => this.setTime(), 1000);
  },
  methods: {
    setTime() {
      const time = new Date();
      const second = ('' + time.getSeconds()).padStart(2, 0);
      const minute = ('' + time.getMinutes()).padStart(2, 0);
      const hour = ('' + time.getHours()).padStart(2, 0);
      this.time = hour + ":" + minute + ":" + second;
      this.time === '00:00:00' ? this.setDate() : null;
    },
    setDate() {
      const time = new Date();
      const day = time.getDate();
      const month = time.toLocaleString('en', {month: 'long'});
      const year = 1900 + time.getYear();
      this.date = day + " " + month + " " + year;
    }
  }
}
</script>