$.validator.setDefaults({
  
});

$("#myform").validate({
  rules: {
    email_address: {
      required: true,
      email: true,
      remote: {url:SITE_URL+"/subscribe_valid_mail?type=1", type:"GET"}
    }
  },
  messages: {
    email_address: {
      remote: 'Email Already Exists'
    }
  }
});