document.addEventListener('click', function(e)
{
  if(e.target.matches('.confirm-delete'))
{
    if(!confirm('Are you sure?')) 
        e.preventDefault();
}
}
);
