--  registers_table

--  |name        |reg#    |convention           |                        
--  |$ZERO       |0       |constant 0           |                    
--  |$at         |1       |reserved for compiler|                
--  |$v0-$v1     |2-3     |results              |                
--  |$a0-$a3     |4-7     |arguments            |                    
--  |$t0-$t7     |8-15    |caller-temps         |            
--  |$s0-$s7     |16-23   |caller-saved         |    
--  |$t8-$t9     |24-25   |caller-temps         |    
--  |$k0-$k1     |26-27   |reserved for OS      |            
--  |$gp         |28      |global_pointer       |            
--  |$sp         |29      |stack_pointer        |            
--  |$fp         |30      |frame_pointer        |            
--  |$ra         |31      |return_pointer       |



-- functions_table
-- R_type instruction code and assemply
-- first assemply :
--      add $rd,$rs,$rt
--      sub ///////////
--      and ///////////
--      or  ///////////
--      slt ///////////
--      nor ///////////
-- second instruction code :
--      |opcode  |rs_5bit  |rt_5bit  |rd_5bit  |shfamt_5bit  |func_6bit   | 
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000000   and|      
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000001   or |    
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000010   add|    
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000011   sub|
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000100   slt|
--      |000000  |reg_add  |reg_add  |reg_add  |shift_const  |000101   nor|
-- --------------------------------------------
-- i_type instruction code and assemply
-- first assemply :
--      addi $rt,$rs,const_imm
--      sumi ///////////////// 
--      andi /////////////////
--      ori  /////////////////
--      slti /////////////////
--      nori /////////////////
--      lw   $rt,const_imm($rs) load word form memory_data to reg_file 
--      sw   $rt,const_imm($rs) send word from reg_file to memory_data
--      bne  $rt,$rs,L(addr)    move to this instruction
-- second instruction code :
--      |opcode  |rs_5bit  |rt_5bit  |const_imm(16bit)  |
--      |000001  |reg_add  |reg_add  |const_number      |andi      
--      |000010  |reg_add  |reg_add  |const_number      |ori    
--      |000011  |reg_add  |reg_add  |const_number      |addi    
--      |000100  |reg_add  |reg_add  |const_number      |subi 
--      |000101  |reg_add  |reg_add  |const_number      |slti
--      |000110  |reg_add  |reg_add  |const_number      |nori
--      |000111  |reg_add  |reg_add  |const_number      |lw  
--      |001000  |reg_add  |reg_add  |const_number      |sw  
--      |001001  |reg_add  |reg_add  |const_number      |bne range -128KB to 127KB from next_inst
-- J_type instruction code and assemply
-- first assemply :
--      j inst_addr
-- seconde instruction code :
--      |opcode  |instr_add(26bit) |                                  
--      |001010  |const_number     |256 MB access

library ieee;
use ieee.std_logic_1164.all;
use ieee.numeric_std.all;
package inst_pck is
    -- registers in reg_file
    constant ZERO : std_logic_vector(4 downto 0) := "00000"; 
    constant at   : std_logic_vector(4 downto 0) := "00001"; 
    constant v0   : std_logic_vector(4 downto 0) := "00010"; 
    constant v1   : std_logic_vector(4 downto 0) := "00011";  
    constant a0   : std_logic_vector(4 downto 0) := "00100"; 
    constant a1   : std_logic_vector(4 downto 0) := "00101";   
    constant a2   : std_logic_vector(4 downto 0) := "00110";    
    constant a3   : std_logic_vector(4 downto 0) := "00111"; 
    constant t0   : std_logic_vector(4 downto 0) := "01000"; 
    constant t1   : std_logic_vector(4 downto 0) := "01001";   
    constant t2   : std_logic_vector(4 downto 0) := "01010";  
    constant t3   : std_logic_vector(4 downto 0) := "01011"; 
    constant t4   : std_logic_vector(4 downto 0) := "01100"; 
    constant t5   : std_logic_vector(4 downto 0) := "01101";  
    constant t6   : std_logic_vector(4 downto 0) := "01110";  
    constant t7   : std_logic_vector(4 downto 0) := "01111"; 
    constant s0   : std_logic_vector(4 downto 0) := "10000"; 
    constant s1   : std_logic_vector(4 downto 0) := "10001";  
    constant s2   : std_logic_vector(4 downto 0) := "10010";  
    constant s3   : std_logic_vector(4 downto 0) := "10011"; 
    constant s4   : std_logic_vector(4 downto 0) := "10100"; 
    constant s5   : std_logic_vector(4 downto 0) := "10101";  
    constant s6   : std_logic_vector(4 downto 0) := "10110";  
    constant s7   : std_logic_vector(4 downto 0) := "10111"; 
    constant t8   : std_logic_vector(4 downto 0) := "11000"; 
    constant t9   : std_logic_vector(4 downto 0) := "11001"; 
    constant k0   : std_logic_vector(4 downto 0) := "11010"; 
    constant k1   : std_logic_vector(4 downto 0) := "11011"; 
    constant gp   : std_logic_vector(4 downto 0) := "11100"; 
    constant sp   : std_logic_vector(4 downto 0) := "11101"; 
    constant fp   : std_logic_vector(4 downto 0) := "11110"; 
    constant ra   : std_logic_vector(4 downto 0) := "11111"; 

    -- R_type funcs
    constant andr  : std_logic_vector(5 downto 0) := "000000";    
    constant orr   : std_logic_vector(5 downto 0) := "000001";
    constant addr  : std_logic_vector(5 downto 0) := "000010";
    constant subr  : std_logic_vector(5 downto 0) := "000011";
    constant sltr  : std_logic_vector(5 downto 0) := "000100";
    constant norr  : std_logic_vector(5 downto 0) := "000101";
    -- I_type op_codes 
    constant andi : std_logic_vector(5 downto 0) := "000001";
    constant ori  : std_logic_vector(5 downto 0) := "000010";
    constant addi : std_logic_vector(5 downto 0) := "000011";
    constant subi : std_logic_vector(5 downto 0) := "000100";
    constant slti : std_logic_vector(5 downto 0) := "000101";
    constant nori : std_logic_vector(5 downto 0) := "000110";
    constant lw   : std_logic_vector(5 downto 0) := "000111";
    constant sw   : std_logic_vector(5 downto 0) := "001000";
    constant beq  : std_logic_vector(5 downto 0) := "001001";
    constant bne  : std_logic_vector(5 downto 0) := "001010";
    -- J_type op_codes
    constant j    : std_logic_vector(5 downto 0) := "001011";


    -- R_type instruction function declaration
    function r_inst(
        func : std_logic_vector(5 downto 0) := (others => '0');
        rd : std_logic_vector(4 downto 0) := (others => '0');
        rs : std_logic_vector(4 downto 0) := (others => '0');
        rt : std_logic_vector(4 downto 0) := (others => '0')
    )return std_logic_vector;

    -- I-type instruction functions declaration
    function i_inst(
        op_code : std_logic_vector(5 downto 0) := (others => '0');
        rt : std_logic_vector(4 downto 0) := (others => '0');
        rs : std_logic_vector(4 downto 0) := (others => '0');
        const_imm : integer range -2**15 to 2**15-1 := 0 
    )return std_logic_vector;
    -- J-type instruction functions declaration
    function j_inst(
        op_code : std_logic_vector(5 downto 0) := (others => '0');
        inst_addr : integer range 0 to 2**26-1 := 0
    )return std_logic_vector;

--------------------------------------------------------------------
end package;

package body inst_pck is

    -- R-type instruction functions initialization
    function r_inst(
        func : std_logic_vector(5 downto 0) := (others => '0');
        rd : std_logic_vector(4 downto 0) := (others => '0');
        rs : std_logic_vector(4 downto 0) := (others => '0');
        rt : std_logic_vector(4 downto 0) := (others => '0')
    )return std_logic_vector is
            begin
                return "000000" & rs & rt & rd & "00000" & func;
    end function;

    -- I-type instruction functions initalization
    function i_inst(
        op_code : std_logic_vector(5 downto 0) := (others => '0');
        rt : std_logic_vector(4 downto 0) := (others => '0');
        rs : std_logic_vector(4 downto 0) := (others => '0');
        const_imm : integer range -2**15 to 2**15-1 := 0
    )return std_logic_vector is
        begin
            return op_code & rs & rt & std_logic_vector(to_signed(const_imm,16));
    end function;

    -- J-type instruction functions initalization
    function j_inst(
        op_code : std_logic_vector(5 downto 0) := (others => '0');
        inst_addr : integer range 0 to 2**26-1 := 0 
    )return std_logic_vector is
        begin
            return op_code & std_logic_vector(to_unsigned(inst_addr,26));
    end function;

end package body;