 <ul id="nav">
            <li><a href="#">Main</a>
                <ul>
                    
                    <li><a href="{{ URL::to('time') }}">Time Attendance</a></li>
                     <li><a href="{{ URL::to('udp') }}">Edit Time</a></li>
                    
                </ul>
            </li>
             @if(Auth::user()->role <=1 )
            <li><a href="#">Application</a>
                <ul>
                     <li><a href="{{ URL::to('application') }}">Application List </a></li>
                      <li><a href="{{ URL::to('overtime') }}">Application</a></li>
                     
                  
                </ul>
            </li>

            @endif
                @if(Auth::user()->role >= 2)
            <li><a href="#">Conrfim</a>
                <ul>
                    <li><a href="{{ URL::to('approve') }}">Approve </a></li>  
                    <li><a href="{{ URL::to('importExport') }}">Inport Excell</a></li>  
                    <li><a href="{{ URL::to('timeMan') }}">Approve In Out</a></li>
                </ul>
            </li>
                @else 
                <li><a href="#"><style>p.ident{padding-left:8em}</style></a>
                 </li>   
                @endif
                
             <li><a href="#"> </a>
               </li>
                   <li><a href="#"> </a>
               </li>
             <li id="navus"><a  href="#">{{ Auth::user()->name }}</a>
             <ul>
               @if(Auth::user()->role > 0)
                 <li><a href="{{ URL::to('usrMaster') }}">Users</a></li>
			  <li><a href="{{ URL::to('usrAMaster') }}">Annual Leave</a></li>
 
                  @endif
   <li><a href="{{ URL::to('reportSearch') }}">Report</a></li>
    <li><a href="{{ URL::to('canSearch') }}">Candidates</a></li>
                <li ><a  href="{{ url('/logout') }}"> Logout </a></li>
             </ul>
            </li>


            
        </ul>