 <ul id="nav">
            <li><a href="#"></a>
               
            </li>
              <li><a href="#"></a>
              
            </li>
            <li><a href="#"></a>
               
            </li>
            <li><a href="#"></a>
              
            </li>
                 <li><a href="#"></a>
              
            </li>
            <li id="navus"><a  href="#">{{ Auth::user()->name }}</a>
             <ul>
                 <li><a href="{{ URL::to('dailyreport') }}">Top Menu</a></li>
			 <li><a href="{{ URL::to('canSearch') }}">Candidates</a></li>
                <li><a href="{{ URL::to('time') }}">Time Keeper</a></li>
               <li><a href="{{ URL::to('reportSearch') }}">Report</a></li>
				 <li><a href="{{ URL::to('dailyreport/create') }}">New Report</a></li>
                <li ><a  href="{{ url('/logout') }}"> Logout </a></li>
             </ul>
            </li>

            
            
        </ul>