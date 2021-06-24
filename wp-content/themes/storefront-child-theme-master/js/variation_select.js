// Select variation buttons on product page
function variationSelected(event) {
  var variationId = event.target.value;

  var e = document.getElementsByName('variation_id')[0]
  if (e) {
    e.value = variationId;
  }

  var b = document.getElementsByClassName('single_add_to_cart_button')[0];
  if (b) {
    if (variationId) {
      b.classList.remove('disabled')
    } else {
      b.classList.add('disabled')
    }
  }
}
