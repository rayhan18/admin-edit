
<!--first stepe---in any admin blade file--->
<!-- note: 
this only content not img
give id name main div
give input id one 0r more
give button id -->
 <!---edit data modal --->
   <div class="modal fade" id="editImgModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">edit Content</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editNewContent">
      <div class="modal-body" >
     
  <div class="form-group">
    <label for="clientName">Title</label>
    <input type="text" class="form-control" id="editTitle" name="clientName" placeholder="name">
  </div>
  
  
  <div class="form-group">
    <label for="exampleFormControlTextarea1">Description</label>
    <textarea class="form-control" id="editAboutdesc" name="addDescription" rows="3"></textarea>
  </div>  
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
        <button type="submit" id='updateAboutData'data-id='' class="btn btn-primary btn-sm">update</button>
      </div>
      </form>
    </div>
  </div>
</div>
@endsection
@section('script')
<!-- <!============part of add data (database to admin table)=====================> -->
 $.each(jesonData,function(i){
          $('<tr>').html(
              "<td>" +jesonData[i].id+ "</td>"+
              "<td>" +jesonData[i].title+ "</td>"+
              "<td>" +jesonData[i].description+ "</td>"+
			  
			   <!-- <--importent line id button clink button open modal with data--> -->
			   
             "<td> <a href='#'id='editAbout' data-id='" +jesonData[i].id+ "' class='edit btn btn-primary w-10 btn-sm' title='Edit' data-toggle='tooltip'><i class='material-icons'>&#xE254;</i></a><a href='#' id='aboutDelete' class='delete btn btn-danger btn-sm' data-id='" +jesonData[i].id+ "' title='Delete' data-toggle='tooltip'><i class='material-icons'>&#xE872;</i></a></td>"
               
            ).appendTo("#aboutstableBody")
			
 }
<!-- <!==============hear anather part= each function=====================> -->

<!--2nd stepe---in any admin blade file--->

<script type=text/javascript>
//adit Data add modal(1)
 $(document).on('click','#editAbout',function(e){
   e.preventDefault();
const id=$(this).data('id')
$('#editImgModal').modal('show')
$('#updateAboutData').data('id',id)
    getEditAboutData(id);
 })
//adit Data add modal with data(2)
 function getEditAboutData(id){
   axios.get('/getEditAboutData/'+id).then(respons=>{
    $('#editTitle').val(respons.data.title)
    $('#editAboutdesc').val(respons.data.description)
   }).catch(error=>{
     alert(error.massage)
   })
 }
 //adit Data  (3)
 $(document).on('click','#updateAboutData',function(e){
e.preventDefault();
const data=$(this).data('id')
const title=$('#editTitle').val()
const desc=$('#editAboutdesc').val()

    axios.post('/updateAboutData',{
      id:data,
      title:title,
      description:desc,
    }).then(respons=>{
            if(respons.data==1){
            alert('data edit success')
            aboutData();
            $('#editImgModal').modal('hide');
            }else{
              alert('data edit fail')
            }
   
    }).catch(error=>{
      alert(error.massage) 
    })

  })
</script>
@endsection

 <!--go controller-//edit data--->

     function getEditAboutData($id){
        $result= About::find($id);   
        return $result;
     }

     function updateAboutData(Request $request){
       $id=$request->id;
       $title=$request->title;
       $desc=$request->description;

       $result= About::where('id',$id)->update([
           'title'=>$title,
           'description'=>$desc,
          ]);
       if($result==true){
        return 1;
          }else{
          return 0;
        }
     }

<!--go Route --//edit admin data table get two methode for one page(get and post)-->

    Route::get('/getEditAboutData/{id}','Admin\AboutController@getEditAboutData');
    Route::post('/updateAboutData','Admin\AboutController@updateAboutData');
