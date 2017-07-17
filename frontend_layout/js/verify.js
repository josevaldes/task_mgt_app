
function verify()
{
  var inputs = document.getElementsByTagName("input");
  for(var i = 0; inputs.length; ++i)
  {
    if(inputs[i].value == "")
    {
      alert("Field is empty");
      inputs[i].focus();
      return false;
    }
  }
  return true;
}
