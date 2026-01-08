const main_input = document.querySelector("main input");
const main_button = document.querySelector("main button.login-link");

main_input.addEventListener('input', () => {
  if(main_input.value)
  {
    main_button.style.background = "#5063F9";
  }
  else
  {
    main_button.style.background = "#B7C6FF";
  }
});
