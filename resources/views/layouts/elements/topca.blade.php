<ul id="nav">
            <li><a href="#">Company</a>
                <ul>
                    <li><a href="{{ URL::to('client') }}">Company</a></li>
                    <li><a href="{{ URL::to('client/create') }}">Create New</a></li>

                    
                    
                </ul>
            </li>
              <li><a href="#">Division</a>
                <ul>
                           <li><a href="{{ URL::to('division') }}">Division</a></li>
                            <li><a href="{{ URL::to('division/create') }}">Create New</a></li>
                </ul>
            </li>
            <li><a href="#">Order</a>
                <ul>
                    <li><a href="{{ URL::to('order') }}">Order</a></li>
                    <li><a href="{{ URL::to('order/create') }}">Create New </a></li>
                </ul>
            </li>
            <li><a href="#">PIC</a>
                <ul>
                   <li><a href="{{ URL::to('pic') }}">PIC</a></li>
                     <li><a href="{{ URL::to('pic/create') }}">New PIC</a></li>
                    
                </ul>
            </li>
                 <li><a href="#">Candidate</a>
                <ul>
                   <li><a href="{{ URL::to('canSearch1') }}">Candidate List</a></li>
                    <li><a href="{{ URL::to('candidate/create') }}">Create New </a></li>
                   
                    
                </ul>
            </li>
            <li id="navus"><a  href="#">{{ Auth::user()->name }}</a>
             <ul>
                  <li><a href="{{ URL::to('dailyreport') }}">Top Menu</a></li>
                <li><a href="{{ URL::to('time') }}">Time Keeper</a></li>
               <li><a href="{{ URL::to('reportSearch') }}">Report</a></li>
               <li><a href="{{ URL::to('dailyreport/create') }}">New Report</a></li>

                 <li><a href="{{ URL::to('masterSearch') }}">Master</a></li>
                      <li ><a  href="{{ url('/editProfile') }}"> Edit Profile </a></li>
                <li ><a  href="{{ url('/logout') }}"> Logout </a></li>
             </ul>
            </li>

            
            
        </ul>