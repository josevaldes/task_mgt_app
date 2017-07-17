var deadline_field = document.getElementById('deadline');
deadline_field.onblur = function()
{
  if(isNaN(this.value))
  {
    this.value = '';
    alert('Deadline field should be a number');
    this.focus();
  }
};
