function Product (name, firstSelect, secondSelect)
{
	this.name = name;
	this.firstSelect = firstSelect;
	this.secondSelect = secondSelect;
}

function Select(id)
{
	
	this.id = id;
}
Product.prototype.addEvents = function(){
	$("#"+firstSelect.id).on("change",function(){
	alert(123);
	})
	$("#"+secondSelect.id).on("change",function(){
	alert(456);
	})
}
var firstSelect = new Select("kur");
var secondSelect = new Select("kon")
var products = [new Product("KUR", firstSelect, secondSelect)];

for(var product of products)
{
	var current = $('<div><select id="' + product.firstSelect.id + '"><option>Paco</option><option>kiro</option></select><select id="' + product.secondSelect.id +'"><option>toncho</option><option>bonboncho</option></select></div>');
	$(document.body).append(current);
	product.addEvents();
}
