/*

Notes: 
 To edit Email, enter new one on the input box then press outside the input box to submit
*/



/*
Prototype, AJAX, Regex
*/


// Prototypes

var Employee = {
	setEmail: function(str){
		this.email = str;
	},
	getGrossPay: function(){
		return this.hourlyRate * this.hoursWorked;
	},
	setHourlyRate: function(hr){
		this.hourlyRate = hr;
	}
}

function employee(personObj,employeeType){
	self = this;
	self.id = personObj.id;
	self.first_name = personObj.first_name;
	self.last_name = personObj.last_name;
	self.email = personObj.email;
	self.gender = personObj.gender;
	self.hourlyRate = 100;
	self.hoursWorked = 0;
	self.daysWorked = 0;
	self.empType = employeeType;
}


/* set prototype for Employee constructor */
employee.prototype = Employee;

/* prototype for both database */
var Database = {
	dataArray : [],
	setDataArray : function(arr){
		this.dataArray = arr;
		return this;
	},
	getDataArray : function(){
		return this.dataArray;
	},
	getObj: function(){
		for(i=0;i<this.length;i++){
			console.log(this[i].id);
		}
	},
	getSingleData: function(id){ // returns a single data using ID
		self = this;
		for(i=0;i<self.dataArray.length;i++){
			if(self.dataArray[i].id==id){
				return this.dataArray[i];
			}
		}
	},
	addData: function(obj){
		//add validation here if we have time
		self = this;
		let idArr = [];
		for(i=0;i<self.dataArray.length;i++){
			idArr.push(self.dataArray[i].id);
		}
		if(idArr.indexOf(obj.id)==-1){
			self.dataArray.push(obj);
		}else{
			alert("Existing");
		}
	},
	updateData: function(id,obj){
		self = this;
		for(i=0;i<self.dataArray.length;i++){
			if(self.dataArray[i].id==id){
				self.dataRray[i] = obj;
			}
		}
	}
}

/*
function that returns an array to set non-persistent Database: dbArray after receiving response from AJAX request
*/
function generateDB(jsonResponse){
	var rows = jsonResponse.length;
	var Arr = [];
	for(i=0;i<rows;i++){
		Arr.push(jsonResponse[i]);
	}
	return Arr;
}

/* function to render data into a table. Called whenever there's an update with dbArray 
	also binds data-attributes
*/
function renderData(dbObj,node,type){
	
	var dbArray = dbObj.getDataArray();
	node.find("tr").next("tr").remove();
	for(i=0;i<dbArray.length;i++){
		var dataRow = $("<tr data-person='"+dbArray[i].id+"' data-clicked=false><td>"+dbArray[i].id+"</td><td>"+dbArray[i].first_name+"</td><td>"+dbArray[i].last_name+"</td><td>"+dbArray[i].email+"</td><td>"+dbArray[i].gender+"</td></tr>");
		if(type==1){ // type1 = PersonTablle, type2 = EmployeeTable
			dataRow.on("click",function(){
			rowClickHandler(this);
			});
		}if(type==2){
			//append  a diff node
			var emailEditBtn = $("<td><button data-person='"+dbArray[i].id+"'>Edit Email</button></td>");
			emailEditBtn.on("click",function(){
				self = this;
				editEmailHandler(self);
			});
			var empType = $("<td>"+dbArray[i].empType+"</td>");
			empType.appendTo(dataRow);
			
			var empHrlyRate = $("<td>"+dbArray[i].hourlyRate+"</td>");
			empHrlyRate.appendTo(dataRow);
			
			if(dbArray[i].totalOjtHrs){
				var ojtTd = $("<td>"+dbArray[i].totalOjtHrs+"</td>");
				ojtTd.appendTo(dataRow);
			}else{
				dataRow.append("<td></td>");
			}
			if(dbArray[i].daysWorked){
				var dwTd = $("<td>"+dbArray[i].daysWorked+"</td>");
				dwTd.appendTo(dataRow);
			}else{
				dataRow.append("<td></td>");
			}
			emailEditBtn.appendTo(dataRow);
			
			var getGrossPayBtn = $("<td><button data-person='"+dbArray[i].id+"'>get gross pay</button></td>");
			getGrossPayBtn.on("click",function(){
				//console.log(dbObj.dataArrayd[0].getGrossPay());
				alert("Total GrossPay: "+dbArray[0].getGrossPay());
			});
			getGrossPayBtn.appendTo(dataRow);
		}
		node.append(dataRow);
	}
}

/*
function to render a selection for a row
*/
function rowClickHandler(self){
	if(self.dataset.clicked=='false'){
		console.log(self.dataset.person);
		var personId = self.dataset.person;
		var selectEmpType = $("<select class='empType-select'><option value='1'>Full-time</option><option value='2'>Part-time</option><option value='3'>Trainee</option></select>");
		selectEmpType.change(function(){
			getForm($(this).find(":selected").val(),personId);
		});
		
		$(self).append(selectEmpType);
		self.dataset.clicked = 'true';
	}
}
/*
function for edit email
*/
function editEmailHandler(self){
	var emailForm = $("<input type='text' placeholder='Enter new Email'>");
	emailForm.on("focusout",function(){
		if($(this).val()){
			//validate input value
			if(validateEmail($(this).val())){
				$(this).closest("tr").find("td:nth-child(4)").html($(this).val());
			}else{
				alert("Invalid Email");
			}
		}
	});
	$(self).replaceWith(emailForm);
}

/* dynamically show a form that depends upon the type of Employee */
function getForm(empVal,personId){
	
	if(empVal=='1'){
		console.log("Full-time");
		$(".partTime-form,.trainee-form").hide();
		$(".fullTime-form").show();
		$(".emp-form").data("empId",personId);
		$(".emp-form").data("empType","full-time");
	}else if(empVal=='2'){
		console.log("Part-time");
		$(".fullTime-form,.trainee-form").hide();
		$(".partTime-form").show();
		$(".emp-form").data("empId",personId);
		$(".emp-form").data("empType","part-time");
	}else if(empVal=='3'){
		console.log("Trainee");
		$(".fullTime-form,.partTime-form").hide();
		$(".trainee-form").show();
		// set form person-id
		$(".emp-form").data("empId",personId);
		$(".emp-form").data("empType","trainee");
	}
	
}
/*
function for validating email using RegEx
*/
function validateEmail(eMailInput){
	
	var regex = /([a-z0-9][-a-z0-9_\+\.]*[a-z0-9])@([a-z0-9][-a-z0-9\.]*[a-z0-9]\.(arpa|root|aero|biz|cat|com|coop|edu|gov|info|int|jobs|mil|mobi|museum|name|net|org|pro|tel|travel|ac|ad|ae|af|ag|ai|al|am|an|ao|aq|ar|as|at|au|aw|ax|az|ba|bb|bd|be|bf|bg|bh|bi|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|cr|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|ee|eg|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gg|gh|gi|gl|gm|gn|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|im|in|io|iq|ir|is|it|je|jm|jo|jp|ke|kg|kh|ki|km|kn|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|mv|mw|mx|my|mz|na|nc|ne|nf|ng|ni|nl|no|np|nr|nu|nz|om|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tl|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)|([0-9]{1,3}\.{3}[0-9]{1,3}))/;
	// regex that checks string if it has valid email + domain.
	
	return regex.test(eMailInput);
}

$(document).ready(function(){
	
	var dbArray = Object.create(Database);
	var empArray = Object.create(Database);

/*---------------------------------------------------------------------------------------------------------------------- */
	$.empArray = empArray;
	//Ajax call for mock-data, after successful response - parse data into an array and set it into a mock database
	$.ajax({
		url:"json/MOCK_DATA.json",
		success: function(data){
			dbArray.setDataArray(generateDB(data));
			renderData(dbArray,$("#DataTable"),1); // pass database and table-node
		}
	});

	$(".emp-form").submit(function(e){
		e.preventDefault();
		alert("Submitted.\n Please click Generate Employee Table");
		console.log(dbArray.getSingleData($(".emp-form").data("empId")));
		// 
//		console.log($("input[name=t-hr]").val());
		var person = dbArray.getSingleData($(".emp-form").data("empId"));
		var emp;
		if($(this).data("empType")=="trainee"){
			emp = new employee(person,"trainee");
			if($("input[name=t-hr]").val()){
				emp.setHourlyRate($("input[name=t-hr]").val());
			}
			if($("input[name=t-tOjt-hrs]").val()){
				emp.totalOjtHrs = $("input[name=t-tOjt-hrs]").val();
			}
			if($("input[name=t-nh]").val()){
				emp.hoursWorked = $("input[name=t-nh]").val();
			}
		}
		if($(this).data("empType")=="part-time"){
			emp = new employee(person,"part-time");
			if($("input[name=pt-hr]").val()){
				emp.setHourlyRate($("input[name=pt-hr]").val());
			}
			if($("input[name=pt-nh]").val()){
				emp.hoursWorked =$("input[name=pt-nh]").val();
			}
		}
		if($(this).data("empType")=="full-time"){
			emp = new employee(person,"full-time");
			if($("input[name=ft-ndw]").val()){
				emp.daysWorked = $("input[name=ft-ndw]").val();
			}
		}
		empArray.addData(emp);
	});
	
	$(".partTime-form, .fullTime-form, .trainee-form").on("mouseleave",function(){

			$(this).fadeOut("slow");
		
	});
	
	$("#dbEmp").click(function(){
		console.log(empArray.getDataArray());
		renderData(empArray,$("#EmployeeDataTable"),2);
	});
	
	
/* 
Function for search in Persons table
*/
	$("#SearchInput").on("keypress onkeyup mouseout onchange",function(){
		var searchVal = $(this).val();
		var regEx = new RegExp(searchVal,'i');
		if(dbArray){
			$("#DataTable").find("tr").next().remove();
			for(i=0;i<dbArray.dataArray.length;i++){	if(regEx.test(dbArray.dataArray[i].first_name)||regEx.test(dbArray.dataArray[i].last_name)){				
				var pTableRow = $("<tr data-person='"+dbArray.dataArray[i].id+"' data-clicked=false><td>"+dbArray.dataArray[i].id+"</td><td>"+dbArray.dataArray[i].first_name+"</td><td>"+dbArray.dataArray[i].last_name+"</td><td>"+dbArray.dataArray[i].email+"</td><td>"+dbArray.dataArray[i].gender+"</td>");
				pTableRow.on("click",function(){
				rowClickHandler(this);
				});
				$("#DataTable").append(pTableRow);
				}
			}
		}
	});
	
});





























